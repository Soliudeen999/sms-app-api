<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Rules\IsPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Z][a-z]+\s[A-Z][a-z]+(?:\s[A-Z][a-z]+)?$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::default()],
            'phone' => ['sometimes', 'string', new IsPhoneNumber],
        ];
    }
}
