<?php

declare(strict_types=1);

namespace App\Services\Campaign;

use App\Enums\Campaign\CampaignRecipientType;
use App\Enums\Campaign\CampaignStatus;
use App\Enums\Message\MessageType;
use App\Enums\Providers\SmsProviders;
use App\Jobs\ProcessCampaignSending;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CampaignService
{

    private Campaign $campaignModel;

    public function __construct()
    {
        $this->campaignModel = new Campaign;
    }

    public function getAll(?User $user): LengthAwarePaginator
    {
        return $this->campaignModel->query()
            ->when($user, fn($query) => $query->where('user_id', $user->id))
            ->filter()
            ->search()
            ->sort()
            ->paginate();
    }

    public function getSingle(Campaign|int $campaign): Campaign
    {
        if ($campaign instanceof Campaign) {
            return $campaign->load(['messages', 'owner:id,name']);
        }

        return $this->campaignModel->query()
            ->with(['messages', 'owner:id,name'])
            ->findOrFail($campaign);
    }

    public function getCampaignMessages(Campaign $campaign): LengthAwarePaginator
    {
        return $campaign->messages()
            ->filter()
            ->search()
            ->sort()
            ->paginate();
    }

    public function store(User $user, array $data): Campaign
    {
        $data['user_id'] ??= auth()->id();
        $data['provider'] = SmsProviders::T2frocoms;  // Default provider will be set dynamically later using settings package

        $campaign = DB::transaction(function () use ($data) {
            return $this->campaignModel->create($data);
        });

        if ($campaign->type->is(MessageType::INSTANT)) {
            ProcessCampaignSending::dispatchAfterResponse($campaign);
        }

        return $campaign;
    }


    public function update(Campaign $campaign, array $data): Campaign
    {
        DB::transaction(function () use ($data, $campaign) {
            $campaign->update($data);
        });

        if ($campaign->type->is(MessageType::INSTANT)) {
            ProcessCampaignSending::dispatchAfterResponse($campaign);
        }

        return $campaign;
    }

    public function delete(Campaign $campaign): void
    {
        $campaign->delete();
    }

    public function storeMessagesOutofCampaign(Campaign $campaign)
    {
        $type = $campaign->recipient_type->value;

        switch ($type) {
            case CampaignRecipientType::CONTACT_IDS:
                $this->createMsgOutOfContactIds($campaign);
                break;

            default: // Phone Number is believe to be default
                $this->createMsgFromPhoneNumbers($campaign);
                break;
        }
    }

    private function createMsgFromPhoneNumbers(Campaign $campaign): void
    {
        $allNumbers = array_merge($campaign->recipients, $campaign->extra_recipient_numbers ?? []);

        $messages = collect($allNumbers)->map(fn($phone_number) => [
            'phone_number' => $phone_number,
            'campaign_id' => $campaign->id,
            'provider' => $campaign->provider,
            'type' => $campaign->type,
            'user_id' => $campaign->user_id,
            'title' => $campaign->title,
            'body' => $campaign->body,
        ])->toArray();

        Message::insert($messages);
    }

    private function createMsgOutOfContactIds(Campaign $campaign): void
    {
        $contacts = Contact::query()->whereIn('id', $campaign->recipients)->get(['id', 'phone_number']);

        $messages = $contacts->map(fn($contact) => [
            'contact_id' => $contact->id,
            'phone_number' => $contact->phone_number,
            'campaign_id' => $campaign->id,
            'provider' => $campaign->provider,
            'type' => $campaign->type,
            'user_id' => $campaign->user_id,
            'title' => $campaign->title,
            'body' => $campaign->body,
        ]);

        $allMessages = array_merge($messages, $this->buildExtraNumberAsMessage($campaign));

        Message::insert($allMessages);
    }

    private function buildExtraNumberAsMessage(Campaign $campaign): array
    {
        if (empty($campaign->extra_recipient_numbers ?? [])) return [];

        $messages = collect($campaign->extra_recipient_numbers)->map(fn($phone_number) => [
            'phone_number' => $phone_number,
            'campaign_id' => $campaign->id,
            'provider' => $campaign->provider,
            'type' => $campaign->type,
            'user_id' => $campaign->user_id,
            'title' => $campaign->title,
            'body' => $campaign->body,
        ])->toArray();

        return $messages;
    }

    public function updateCampaignStatus(Campaign $campaign, string $status): void
    {
        $campaign->update([
            'status' => $status,
            'last_processed_at' => now(),
        ]);
    }

    public function getDueScheduledCampaigns(): Collection
    {
        return $this->campaignModel->query()
            ->where('type', MessageType::SCHEDULED)
            ->where('status', CampaignStatus::PENDING)
            ->where('scheduled_at', '<=', now())
            ->get();
    }
}
