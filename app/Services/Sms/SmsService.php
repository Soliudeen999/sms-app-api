<?php

declare(strict_types=1);

namespace App\Services\Sms;

use App\Enums\Providers\SmsProviders;
use App\Services\Sms\Contracts\SendSmsContract;
use App\Services\Sms\Providers\AfricaIsTalkingMessageService;
use App\Services\Sms\Providers\DotgoService;
use App\Services\Sms\Providers\T2froComsService;
use Exception;
use Illuminate\Support\Arr;

final class SmsService
{
    protected static array $providers = [
        SmsProviders::DotGo => DotgoService::class,
        SmsProviders::T2frocoms => T2froComsService::class,
        SmsProviders::AfricaIsTalking => AfricaIsTalkingMessageService::class
    ];

    public static function resolve(string $provider): SendSmsContract
    {
        $service = Arr::get(self::$providers, $provider);

        if (! $service) {
            throw new Exception("Unregistered sms service provider: $provider");
        }

        return app($service);
    }
}
