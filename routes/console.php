<?php

use App\Console\Commands\ProcessScheduledCampaigns;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command(ProcessScheduledCampaigns::class)
    ->everyMinute()
    ->withoutOverlapping();
