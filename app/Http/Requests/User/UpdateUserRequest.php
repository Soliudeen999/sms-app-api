<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Enums\Media\MediaPurpose;
use App\Enums\User\UserRole;
use App\Rules\IsPhoneNumber;
use App\Rules\Media\ValidateMediaId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255', 'regex:/^[A-Z][a-z]+\s[A-Z][a-z]+(?:\s[A-Z][a-z]+)?$/'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'phone' => ['sometimes', 'string', new IsPhoneNumber],
        ];
    }
}
