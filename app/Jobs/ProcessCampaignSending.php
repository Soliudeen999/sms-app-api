<?php

namespace App\Jobs;

use App\Enums\Campaign\CampaignStatus;
use App\Enums\Message\MessageStatus;
use App\Models\Campaign;
use App\Services\Campaign\CampaignService;
use App\Services\Sms\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCampaignSending implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Campaign $campaign) {}

    /**
     * Execute the job.
     */
    public function handle(CampaignService $campaignService): void
    {
        $campaignService->storeMessagesOutofCampaign($this->campaign);

        $provider = SmsService::resolve($this->campaign->provider);

        $phoneNumbers = $this->campaign->messages()->pluck('phone_number')->toArray();

        $response = $provider->sendSms(
            $phoneNumbers,
            $this->campaign->body,
            [],
        );

        $status = MessageStatus::SENT;
        $campaignStatus = CampaignStatus::COMPLETED;

        if (!$response->getStatus()) {
            $status = MessageStatus::FAILED;
            $campaignStatus = CampaignStatus::FAILED;
        }

        $campaignService->updateCampaignStatus($this->campaign, $campaignStatus);

        $this->campaign->messages()->update([
            'status' => $status,
        ]);
    }
}
