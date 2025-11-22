<?php

namespace App\Http\Requests\Campaign;

use App\Enums\Campaign\CampaignRecipientType;
use App\Enums\Campaign\CampaignStatus;
use App\Enums\Message\MessageType;
use App\Exceptions\DisplayableException;
use App\Models\Campaign;
use App\Rules\IsPhoneNumber;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateCampaignRequest extends FormRequest
{
    private $currentCampaignInUpdate = null;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $currentCampaignInUpdate = $this->route()->parameter('campaign');

        if (!($currentCampaignInUpdate instanceof Campaign))
            $currentCampaignInUpdate = Campaign::findOrFail($currentCampaignInUpdate);

        if ($currentCampaignInUpdate->status->isNot(CampaignStatus::PENDING())) {
            throw new DisplayableException("Only pending campaigns can be updated.");
        }
        $this->currentCampaignInUpdate = $currentCampaignInUpdate;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['sometimes', 'string', 'min:3', 'max:255'],
            'body' => ['sometimes', 'min:3', 'max:1000'],
            'type' => ['sometimes', new EnumValue(MessageType::class)],
            'recipient_type' => ['sometimes', new EnumValue(CampaignRecipientType::class)],

            'recipients' => ['sometimes', 'array'],
            // 'recipients.*' => ['required'],

            'extra_recipient_numbers' => ['sometimes', 'array'],
            'extra_recipient_numbers.*' => ['required', new IsPhoneNumber],

            'scheduled_at' => ['required_if:type,' . MessageType::SCHEDULED, 'date', 'after:now'],

            'recurrence_config' => ['required_if:type,' . MessageType::RECURING, 'array'],
            'recurrence_config.recurrence' => ['required_if:type,' . MessageType::RECURING, 'in:daily,weekly,monthly'],
            'recurrence_config.day' => ['required_if:recurrence_config.recurrence,weekly', 'in:mondays,tuesdays,wednesdays,thursdays,fridays,saturdays,sundays'],
            'recurrence_config.day_date' => ['required_if:recurrence_config.recurrence,monthly', 'in:' . implode(',', range(1, 31))],
            'recurrence_config.day_time' => ['required_if:type,' . MessageType::RECURING, 'numeric', 'min:0', 'max:23.59'],
        ];

        $rules['recipients.*'] = match ($this->input('recipient_type')) {
            CampaignRecipientType::CONTACT_IDS => ['required', 'exists:contacts,id'],
            CampaignRecipientType::PHONE_NUMBERS => ['required', new IsPhoneNumber],
            CampaignRecipientType::CONTACT_GROUPS => ['required', 'exists:tags,id'], //Tags are used in grouping
        };

        return $rules;
    }

    public function prepareForValidation(): void
    {
        $this->mergeIfMissing([
            'recipient_type' => $this->route()->parameter('campaign')->recipient_type->value,
        ]);
    }
}
