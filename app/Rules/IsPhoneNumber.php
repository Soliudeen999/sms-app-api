<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->check($value)) {
            $fail('The :attribute field is invalid.');
        }
    }

    protected function check($value): bool
    {
        $phone = str($value)->trim();

        if ($phone->startswith('+234') && $phone->length == 14) {
            return true;
        }

        if ($phone->startswith('234') && $phone->length == 13) {
            return true;
        }

        if ($phone->startswith('0') && $phone->length == 11) {
            return true;
        }

        return false;
    }
}
