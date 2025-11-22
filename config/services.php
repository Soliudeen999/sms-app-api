<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'dotgo' => [
        'base_uri' => env('DOTGO_BASE_URI'),
        'account_id' => env('DOTGO_ACCOUNT_ID'),
        'api_token' => env('DOTGO_API_TOKEN'),
    ],

    'africa_is_talking' => [
        'base_uri' => env('AFRICA_IS_TALKING_BASE_URI'),
        'api_key' => env('AFRICA_IS_TALKING_API_KEY'),
        'username' => env('AFRICA_IS_TALKING_USERNAME'),
    ],

    't2frocoms' => [
        'base_uri' => env('T2FROCOMS_BASE_URI'),
        'api_key' => env('T2FROCOMS_API_KEY'),
    ],

];