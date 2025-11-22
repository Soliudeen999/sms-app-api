<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

if (! function_exists('auth_user')) {
    function auth_user(): ?User
    {
        return auth()->user();
    }
}

if (! function_exists('generate_model_unique_code')) {
    function generate_model_unique_code(Model $model, string $field = 'code', int $length = 10, $codeType = 'int'): string
    {
        $model = new $model();

        do {
            $code = strtoupper(Str::random($length));
        } while ($model->where('code', $code)->exists());

        return $code;
    }
}

if (! function_exists('get_as_boolean')) {

    function get_as_boolean(mixed $value, bool $default = false): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $lowerValue = strtolower($value);

            if (in_array($lowerValue, ['true', '1', 'yes', 'on'])) {
                return true;
            }

            if (in_array($lowerValue, ['false', '0', 'no', 'off'])) {
                return false;
            }
        }

        if (is_int($value)) {
            return $value === 1;
        }

        return $default;
    }
}
