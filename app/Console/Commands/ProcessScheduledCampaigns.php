<?php

namespace App\Console\Commands;

use App\Enums\Campaign\CampaignStatus;
use App\Jobs\ProcessCampaignSending;
use App\Services\Campaign\CampaignService;
use Illuminate\Console\Command;

class ProcessScheduledCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-scheduled-campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all scheduled campaigns that are due to be sent';

    /**
     * Execute the console command.
     */
    public function handle(CampaignService $campaignService): void
    {
        $allDueCampaigns = $campaignService->getDueScheduledCampaigns();

        if ($allDueCampaigns->isEmpty()) {
            return;
        }

        foreach ($allDueCampaigns as $campaign) {
            /** @var \App\Models\Campaign $campaign */
            $campaignService->updateCampaignStatus($campaign, CampaignStatus::PROCESSING);
            ProcessCampaignSending::dispatch($campaign);
        }
    }
}
