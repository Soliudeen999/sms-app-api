<?php

declare(strict_types=1);

namespace App\Services\Sms\Contracts;

interface SendSmsContract extends SmsResponseContract
{
    /**
     * sends sms to given phone numbers
     *
     * @param  array<int, string>  $phone_numbers
     * @param  array<string, string|int|bool>  $options
     */
    public function sendSms(
        array $phone_numbers,
        string $message,
        array $options
    ): SmsResponseContract;
}
