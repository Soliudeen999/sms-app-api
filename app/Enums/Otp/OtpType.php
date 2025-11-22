<?php

declare(strict_types=1);

namespace App\Enums\Otp;

use BenSampo\Enum\Enum;

/**
 * @method static static AUTH()
 * @method static static CONFIRM()
 * @method static static GENERAL()
 * @method static static VERIFY()
 */
final class OtpType extends Enum
{
    const AUTH = 'auth';
    const CONFIRM = 'confirm';
    const GENERAL = 'general';
    const VERIFY = 'verify';


    public function getExpiryTime(): int
    {
        // Time is in Minutes
        return match ($this->value) {
            self::AUTH => 5,
            self::CONFIRM => 2,
            self::GENERAL => 10,
            self::VERIFY => 10,
            default => 10
        };
    }
}
