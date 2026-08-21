<?php

namespace App\Http\Requests;

use App\Enums\RegistrationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegistrationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return ['status' => ['required', Rule::enum(RegistrationStatus::class)]];
    }
}
