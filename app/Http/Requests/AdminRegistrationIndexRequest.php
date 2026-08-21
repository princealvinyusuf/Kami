<?php

namespace App\Http\Requests;

use App\Enums\RegistrationStatus;
use App\Services\RegistrationTargetResolver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRegistrationIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', Rule::enum(RegistrationStatus::class)],
            'type' => ['nullable', Rule::in(RegistrationTargetResolver::TYPES)],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }
}
