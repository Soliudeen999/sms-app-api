<?php

declare(strict_types=1);

namespace App\Utils\Macros;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class HttpMacro extends BaseMacro
{
    public static function AuthAppApiCall()
    {
        Http::macro(
            'dotgo',
            fn(): PendingRequest => Http::acceptJson()
                ->contentType('application/json')
                ->baseUrl(config(key: 'services.dotgo.base_uri'))
        );

        Http::macro(
            'africaIsTalking',
            fn(array $headers = []): PendingRequest => Http::acceptJson()
                ->withHeaders(
                    array_merge([
                        'Content-Type' => 'application/json',
                        'apiKey' => config('services.africa_is_talking.api_key')
                    ], $headers)
                )
                ->baseUrl(config('services.africa_is_talking.base_uri'))
        );

        Http::macro(
            't2fromcoms',
            fn(array $headers = []): PendingRequest => Http::acceptJson()
                ->withHeaders(
                    array_merge([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . config('services.t2frocoms.api_key')
                    ], $headers)
                )
                ->baseUrl(config('services.t2frocoms.base_uri'))
        );
    }
}
