<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegistrationIndexRequest;
use App\Http\Requests\UpdateRegistrationStatusRequest;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegistrationController extends Controller
{
    public function index(AdminRegistrationIndexRequest $request)
    {
        Gate::authorize('viewAny', Registration::class);
        $filters = $request->validated();
        $registrations = $this->filteredQuery($filters)
            ->with('registrable')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.registrations.index', compact('registrations', 'filters'));
    }

    public function update(UpdateRegistrationStatusRequest $request, Registration $registration)
    {
        Gate::authorize('update', $registration);
        $registration->update($request->validated());

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function document(Registration $registration)
    {
        Gate::authorize('view', $registration);
        abort_unless($registration->document_path && Storage::disk('local')->exists($registration->document_path), 404);

        return Storage::disk('local')->download(
            $registration->document_path,
            $registration->document_name ?: 'dokumen-pendaftaran'
        );
    }

    public function export(AdminRegistrationIndexRequest $request): StreamedResponse
    {
        Gate::authorize('viewAny', Registration::class);
        $query = $this->filteredQuery($request->validated())->with('registrable')->orderBy('id');

        return response()->streamDownload(function () use ($query) {
            $stream = fopen('php://output', 'wb');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['ID', 'Tanggal', 'Jenis', 'Program/Layanan', 'Nama', 'Email', 'Telepon', 'Kota', 'Provinsi', 'Pendidikan', 'Institusi', 'Pekerjaan', 'Preferensi Jadwal', 'Status', 'Catatan']);

            foreach ($query->lazyById(500) as $registration) {
                fputcsv($stream, array_map($this->safeCsv(...), [
                    $registration->id,
                    $registration->created_at?->format('Y-m-d H:i:s'),
                    $registration->registration_type,
                    $registration->registrable?->title ?? $registration->registrable?->name,
                    $registration->name,
                    $registration->email,
                    $registration->phone,
                    $registration->city,
                    $registration->province,
                    $registration->education,
                    $registration->institution,
                    $registration->occupation,
                    $registration->preferred_schedule,
                    $registration->status->label(),
                    $registration->notes,
                ]));
            }

            fclose($stream);
        }, 'pendaftaran-'.now()->format('Y-m-d-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filteredQuery(array $filters): Builder
    {
        return Registration::query()
            ->when($filters['q'] ?? null, fn (Builder $query, string $term) => $query->where(
                fn (Builder $query) => $query
                    ->where('name', 'like', '%'.$term.'%')
                    ->orWhere('email', 'like', '%'.$term.'%')
                    ->orWhere('phone', 'like', '%'.$term.'%')
                    ->orWhere('institution', 'like', '%'.$term.'%')
            ))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['type'] ?? null, fn (Builder $query, string $type) => $query->where('registration_type', $type))
            ->when($filters['date_from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '<=', $date));
    }

    private function safeCsv(mixed $value): string
    {
        $value = (string) $value;

        return preg_match('/^[\x00-\x20]*[=+\-@]/', $value) ? "'".$value : $value;
    }
}
