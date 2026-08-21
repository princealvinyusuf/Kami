<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Portfolio;
use App\Support\AdminResourceRegistry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ResourceController extends Controller
{
    public function __construct(private readonly AdminResourceRegistry $registry) {}

    public function index(Request $request, string $resource)
    {
        $config = $this->registry->get($resource);
        $this->authorizeAdmin($request);
        $class = $config['model'];
        $query = $class::query()->with($config['with']);
        $term = trim((string) $request->query('q'));

        if ($term !== '') {
            $query->where(function ($query) use ($config, $term) {
                foreach ($config['search'] as $index => $column) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $query->{$method}($column, 'like', '%'.$term.'%');
                }
            });
        }

        foreach (['status', 'verification_status', 'is_active'] as $filter) {
            if (array_key_exists($filter, $config['fields']) && $request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }
        foreach ($config['fields'] as $name => $field) {
            if ($field['type'] === 'relation' && $request->filled($name)) {
                $query->where($name, $request->integer($name));
            }
        }
        $filterOptions = [];
        foreach ($config['fields'] as $name => $field) {
            if ($field['type'] === 'relation') {
                $filterOptions[$name] = $field['model']::query()->orderBy($field['display'])->get(['id', $field['display']]);
            }
        }

        return view('admin.resources.index', [
            'resource' => $resource,
            'config' => $config,
            'items' => $query->latest('id')->paginate(20)->withQueryString(),
            'filterOptions' => $filterOptions,
        ]);
    }

    public function create(Request $request, string $resource)
    {
        $this->authorizeAdmin($request);

        return $this->form($resource, $this->registry->model($resource));
    }

    public function store(Request $request, string $resource)
    {
        $this->authorizeAdmin($request);
        $model = $this->registry->model($resource);
        $this->persist($request, $resource, $model);

        return redirect()->route('admin.resources.index', $resource)->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Request $request, string $resource, int $id)
    {
        $this->authorizeAdmin($request);

        return $this->form($resource, $this->registry->model($resource, $id));
    }

    public function update(Request $request, string $resource, int $id)
    {
        $this->authorizeAdmin($request);
        $model = $this->registry->model($resource, $id);
        $this->persist($request, $resource, $model);

        return redirect()->route('admin.resources.index', $resource)->with('success', 'Perubahan berhasil disimpan.');
    }

    public function destroy(Request $request, string $resource, int $id)
    {
        $this->authorizeAdmin($request);
        $config = $this->registry->get($resource);
        $model = $this->registry->model($resource, $id);
        $files = [];

        DB::transaction(function () use ($config, $model, &$files) {
            foreach ($config['fields'] as $name => $field) {
                if (in_array($field['type'], ['image', 'file'], true) && $model->{$name}) {
                    $files[] = [$field['disk'], $model->{$name}];
                }
            }
            if ($model instanceof Portfolio) {
                foreach ($model->images as $image) {
                    $files[] = ['public', $image->path];
                }
            }
            $model->delete();
        });

        foreach ($files as [$disk, $path]) {
            Storage::disk($disk)->delete($path);
        }

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function download(Request $request, string $resource, int $id, string $field)
    {
        $this->authorizeAdmin($request);
        $config = $this->registry->get($resource);
        $definition = $config['fields'][$field] ?? abort(404);
        abort_unless($definition['type'] === 'file', 404);
        $model = $this->registry->model($resource, $id);
        abort_unless($model->{$field} && Storage::disk($definition['disk'])->exists($model->{$field}), 404);

        return Storage::disk($definition['disk'])->download($model->{$field});
    }

    private function form(string $resource, Model $item)
    {
        $config = $this->registry->get($resource);
        $options = [];
        foreach ($config['fields'] as $name => $field) {
            if (in_array($field['type'], ['relation', 'multi'], true)) {
                $options[$name] = $field['model']::query()->orderBy($field['display'])->get(['id', $field['display']]);
            }
        }

        return view('admin.resources.form', compact('resource', 'config', 'item', 'options'));
    }

    private function persist(Request $request, string $resource, Model $model): void
    {
        $config = $this->registry->get($resource);
        if (isset($config['fields']['slug']) && blank($request->input('slug'))) {
            $request->merge(['slug' => Str::slug((string) $request->input($config['title']))]);
        }
        $rules = [];
        foreach ($config['fields'] as $name => $field) {
            $rules[$name] = $field['rules'];
            if ($field['type'] === 'slug') {
                $rules[$name][] = Rule::unique($model->getTable(), $name)->ignore($model->getKey());
            }
            if ($field['type'] === 'multi') {
                $rules[$name.'.*'] = $field['item_rules'];
            }
            if ($field['type'] === 'images') {
                $rules[$name.'.*'] = ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
            }
        }
        $rules['remove_uploads'] = ['nullable', 'array'];
        $rules['remove_uploads.*'] = ['string'];
        $rules['remove_portfolio_images'] = ['nullable', 'array'];
        $rules['remove_portfolio_images.*'] = ['integer'];
        if ($resource === 'faqs') {
            $type = $request->input('faqable_type');
            $table = ['training' => 'training_programs', 'certification' => 'certification_schemes', 'service' => 'services'][$type] ?? null;
            $rules['faqable_id'] = $table
                ? ['required', 'integer', Rule::exists($table, 'id')]
                : ['nullable', Rule::prohibitedIf(true)];
        }
        if ($resource === 'competency-units' && $request->filled('code')) {
            $rules['code'][] = Rule::unique('competency_units', 'code')
                ->where('certification_scheme_id', $request->integer('certification_scheme_id'))
                ->ignore($model->getKey());
        }

        $data = $request->validate($rules);
        $oldFiles = [];
        $newFiles = [];

        try {
            DB::transaction(function () use ($request, $resource, $model, $config, $data, &$oldFiles, &$newFiles) {
                $attributes = [];
                foreach ($config['fields'] as $name => $field) {
                    $type = $field['type'];
                    if ($type === 'multi' || $type === 'images') {
                        continue;
                    }
                    if ($type === 'checkbox') {
                        $attributes[$name] = $request->boolean($name);
                    } elseif ($type === 'list') {
                        $attributes[$name] = collect(preg_split('/\R/u', (string) ($data[$name] ?? '')))
                            ->map(fn ($line) => trim($line))->filter()->values()->all();
                    } elseif (in_array($type, ['image', 'file'], true)) {
                        if (in_array($name, $data['remove_uploads'] ?? [], true) && $model->{$name}) {
                            $oldFiles[] = [$field['disk'], $model->{$name}];
                            $attributes[$name] = null;
                        }
                        if ($request->hasFile($name)) {
                            if ($model->{$name}) {
                                $oldFiles[] = [$field['disk'], $model->{$name}];
                            }
                            $path = $request->file($name)->store($field['directory'].'/'.now()->format('Y/m'), $field['disk']);
                            abort_if($path === false, 500, 'Berkas tidak dapat disimpan.');
                            $newFiles[] = [$field['disk'], $path];
                            $attributes[$name] = $path;
                        }
                    } else {
                        $attributes[$name] = $data[$name] ?? null;
                    }
                }

                if ($resource === 'articles' && $model instanceof Article && ! $model->exists) {
                    $attributes['author_id'] = $request->user()->id;
                }
                if (isset($attributes['slug']) && blank($attributes['slug'])) {
                    $attributes['slug'] = Str::slug($attributes[$config['title']] ?? '');
                }

                $model->fill($attributes)->save();

                if (array_key_exists('trainer_ids', $config['fields'])) {
                    $model->trainers()->sync($data['trainer_ids'] ?? []);
                }
                if ($model instanceof Portfolio) {
                    $remove = $data['remove_portfolio_images'] ?? [];
                    foreach ($model->images()->whereIn('id', $remove)->get() as $image) {
                        $oldFiles[] = ['public', $image->path];
                        $image->delete();
                    }
                    $nextSortOrder = ((int) $model->images()->max('sort_order')) + 1;
                    foreach ($request->file('portfolio_images', []) as $index => $file) {
                        $path = $file->store('portfolio/'.now()->format('Y/m'), 'public');
                        abort_if($path === false, 500, 'Gambar tidak dapat disimpan.');
                        $newFiles[] = ['public', $path];
                        $model->images()->create([
                            'path' => $path,
                            'alt_text' => $model->title,
                            'sort_order' => $nextSortOrder + $index,
                        ]);
                    }
                }
            });
        } catch (\Throwable $exception) {
            foreach ($newFiles as [$disk, $path]) {
                Storage::disk($disk)->delete($path);
            }
            throw $exception;
        }

        foreach ($oldFiles as [$disk, $path]) {
            Storage::disk($disk)->delete($path);
        }
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless((bool) $request->user()?->is_admin, 403);
    }
}
