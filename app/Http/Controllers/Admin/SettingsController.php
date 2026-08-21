<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit(Request $request, Settings $settings)
    {
        $this->authorizeAdmin($request);
        $values = [];
        foreach (Settings::DEFINITIONS as $group) {
            foreach ($group['fields'] as $key => $field) {
                $values[$key] = $settings->get($key);
            }
        }

        return view('admin.settings.edit', compact('values'));
    }

    public function update(Request $request, Settings $settings)
    {
        $this->authorizeAdmin($request);
        $rules = [];
        foreach (Settings::DEFINITIONS as $group) {
            foreach ($group['fields'] as $key => $field) {
                $input = str_replace('.', '__', $key);
                $rules[$input] = match ($field['type']) {
                    'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,ico', 'max:2048'],
                    'email' => ['nullable', 'email:rfc', 'max:255'],
                    'url' => ['nullable', 'url:http,https', 'max:2048'],
                    'integer' => ['nullable', 'integer', 'min:1', 'max:10240'],
                    'analytics' => ['nullable', 'string', 'max:80', 'regex:/^[A-Za-z0-9_-]+$/'],
                    'boolean' => ['boolean'],
                    'text' => ['nullable', 'string', 'max:10000'],
                    default => ['nullable', 'string', 'max:255'],
                };
                if ($field['type'] === 'image') {
                    $rules['remove__'.$input] = ['boolean'];
                }
            }
        }
        $data = $request->validate($rules);

        foreach (Settings::DEFINITIONS as $groupKey => $group) {
            foreach ($group['fields'] as $key => $field) {
                $type = $field['type'];
                $input = str_replace('.', '__', $key);
                if ($type === 'image') {
                    if ($request->boolean('remove__'.$input) && ! $request->hasFile($input)) {
                        $old = $settings->get($key);
                        $settings->put($key, null, 'string', $groupKey, true);
                        if (is_string($old)) {
                            Storage::disk('public')->delete($old);
                        }

                        continue;
                    }
                    if (! $request->hasFile($input)) {
                        continue;
                    }
                    $old = $settings->get($key);
                    $path = $request->file($input)->store('branding', 'public');
                    abort_if($path === false, 500, 'Berkas tidak dapat disimpan.');
                    $settings->put($key, $path, 'string', $groupKey, true);
                    if (is_string($old)) {
                        Storage::disk('public')->delete($old);
                    }

                    continue;
                }
                $value = $type === 'boolean' ? $request->boolean($input) : ($data[$input] ?? null);
                $settings->put($key, $value, $type === 'boolean' ? 'boolean' : ($type === 'integer' ? 'integer' : 'string'), $groupKey, in_array($groupKey, ['branding', 'company', 'contact', 'presentation', 'seo', 'legal'], true));
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless((bool) $request->user()?->is_admin, 403);
    }
}
