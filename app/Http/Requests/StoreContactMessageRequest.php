<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
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
            'name' => trim((string) $this->input('name')),
            'email' => mb_strtolower(trim((string) $this->input('email'))),
            'phone' => $phone ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'regex:/^\+?[0-9]{8,15}$/'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['nullable', 'max:0'],
        ];
    }
}
