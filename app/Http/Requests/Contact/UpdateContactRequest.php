<?php

namespace App\Http\Requests\Contact;

use App\Models\Contact;
use App\Rules\IsPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateContactRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'email' => ['sometimes', 'email', 'min:3', 'max:255'],
            'note' => ['sometimes', 'min:3', 'max:500'],
            'phone_number' => ['sometimes', 'string', new IsPhoneNumber],
            'country_code' => ['sometimes', 'string']
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $currentContactInUpdateId = $this->route()->parameter('contact');

                if ($currentContactInUpdateId instanceof Contact) {
                    $currentContactInUpdateId = $currentContactInUpdateId->id;
                }

                $conditions = [
                    'user_id' => auth()->id(),
                    'phone_number' => $this->input('phone_number')
                ];

                $exist = Contact::query()->where($conditions)->where('id', '!=', $currentContactInUpdateId)->first(['id']);

                $validator->errors()->addIf($exist, 'phone_number', 'Phone number is already on your contact list.');
            }
        ];
    }
}
