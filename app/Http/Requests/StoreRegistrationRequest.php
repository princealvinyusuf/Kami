<?php

namespace App\Http\Requests;

use App\Services\RegistrationTargetResolver;
use App\Support\Settings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phone = preg_replace('/[^\d+]/', '', (string) $this->input('phone'));

        if (str_starts_with($phone, '0')) {
            $phone = '+62'.substr($phone, 1);
        } elseif (str_starts_with($phone, '62')) {
            $phone = '+'.$phone;
        }

        $this->merge([
            'phone' => $phone,
            'name' => trim((string) $this->input('name')),
            'email' => mb_strtolower(trim((string) $this->input('email'))),
        ]);
    }

    public function rules(Settings $settings, RegistrationTargetResolver $resolver): array
    {
        $type = (string) $this->route('type');
        $target = $resolver->resolve($type, $this->route('slug'));
        $collectSensitive = (bool) $settings->get('registration.collect_sensitive_fields', false);
        $requireSensitive = $collectSensitive && (bool) $settings->get('registration.require_sensitive_fields', false);
        $maxUploadKb = min(max((int) $settings->get('registration.document_max_kb', 5120), 1), 10240);

        $scheduleRule = Rule::exists(
            $type === 'training' ? 'training_schedules' : 'certification_schedules',
            'id'
        )->where(fn ($query) => $query
            ->where($type === 'training' ? 'training_program_id' : 'certification_scheme_id', $target?->getKey())
            ->where('status', 'scheduled')
            ->where('starts_at', '>=', now()));

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'regex:/^\+?[0-9]{8,15}$/'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'education' => ['required', 'string', 'max:120'],
            'institution' => ['required', 'string', 'max:160'],
            'occupation' => ['required', 'string', 'max:120'],
            'preferred_schedule' => ['nullable', 'string', 'max:160'],
            'schedule_id' => in_array($type, ['training', 'certification'], true)
                ? ['nullable', 'integer', $scheduleRule]
                : ['prohibited'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', "max:{$maxUploadKb}"],
            'nik' => $collectSensitive ? [Rule::requiredIf($requireSensitive), 'nullable', 'digits:16'] : ['prohibited'],
            'gender' => $collectSensitive ? [Rule::requiredIf($requireSensitive), 'nullable', Rule::in(['male', 'female'])] : ['prohibited'],
            'birth_date' => $collectSensitive ? [Rule::requiredIf($requireSensitive), 'nullable', 'date', 'before:today'] : ['prohibited'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Nomor WhatsApp/telepon tidak valid.',
            'schedule_id.exists' => 'Jadwal yang dipilih tidak tersedia.',
            'document.mimes' => 'Dokumen harus berupa PDF, JPG, PNG, DOC, atau DOCX.',
            'document.max' => 'Ukuran dokumen melebihi batas yang diizinkan.',
            'website.max' => 'Permintaan tidak dapat diproses.',
        ];
    }
}
