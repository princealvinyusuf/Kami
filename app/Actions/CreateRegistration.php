<?php

namespace App\Actions;

use App\Mail\AdminRegistrationReceived;
use App\Mail\ParticipantRegistrationReceived;
use App\Models\CertificationSchedule;
use App\Models\Registration;
use App\Models\TrainingSchedule;
use App\Support\Settings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class CreateRegistration
{
    public function __construct(private readonly Settings $settings) {}

    public function handle(array $data, string $type, ?Model $target, ?UploadedFile $document): Registration
    {
        $documentData = [];

        if ($document) {
            $path = $document->store('registrations/'.now()->format('Y/m'), 'local');
            abort_if($path === false, 500, 'Dokumen tidak dapat disimpan.');
            $documentData = [
                'document_path' => $path,
                'document_name' => Str::of(basename($document->getClientOriginalName()))
                    ->replaceMatches('/[^\pL\pN._ -]+/u', '_')
                    ->limit(180, '')
                    ->toString() ?: 'dokumen',
                'document_mime' => $document->getMimeType(),
                'document_size' => $document->getSize(),
            ];
        }

        try {
            $registration = DB::transaction(function () use ($data, $type, $target, $documentData) {
                $schedule = $this->schedule($type, $data['schedule_id'] ?? null);

                $registration = new Registration([
                    ...collect($data)->except(['document', 'schedule_id', 'website'])->all(),
                    ...$documentData,
                    'registration_type' => $type,
                    'status' => 'new',
                    'metadata' => [
                        'source_url' => request()->fullUrl(),
                        'ip_hash' => hash('sha256', (string) request()->ip().config('app.key')),
                    ],
                ]);
                $registration->registrable()->associate($target);
                $registration->schedule()->associate($schedule);
                $registration->save();

                DB::afterCommit(fn () => $this->queueNotifications($registration->fresh()));

                return $registration;
            });
        } catch (Throwable $exception) {
            if (isset($documentData['document_path'])) {
                Storage::disk('local')->delete($documentData['document_path']);
            }

            throw $exception;
        }

        return $registration;
    }

    private function schedule(string $type, mixed $id): ?Model
    {
        if (! $id) {
            return null;
        }

        return $type === 'training'
            ? TrainingSchedule::query()->find($id)
            : CertificationSchedule::query()->find($id);
    }

    private function queueNotifications(Registration $registration): void
    {
        try {
            $adminEmail = $this->settings->get('notifications.registration_admin_email');
            if (is_string($adminEmail) && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::to($adminEmail)->queue(new AdminRegistrationReceived($registration));
            }

            if ($this->settings->get('notifications.registration_confirmation_enabled', false)) {
                Mail::to($registration->email)->queue(new ParticipantRegistrationReceived($registration));
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
