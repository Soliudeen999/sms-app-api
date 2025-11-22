<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Enums\Otp\OtpChannel;
use App\Enums\Otp\OtpType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResendVerificationOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'otp_type' => ['sometimes', 'string', Rule::in(OtpType::getValues())],
            'otp_channel' => ['sometimes', 'string', Rule::in(OtpChannel::getValues())],
        ];
    }

    public function prepareForValidation()
    {
        $this->mergeIfMissing([
            'otp_type' => OtpType::VERIFY,
            'otp_channel' => OtpChannel::MAIL,
        ]);
    }
}
