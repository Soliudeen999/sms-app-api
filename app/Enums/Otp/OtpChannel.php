<?php

declare(strict_types=1);

namespace App\Enums\Otp;

use BenSampo\Enum\Enum;

/**
 * @method static static MAIL()
 * @method static static SMS()
 * @method static static IN_APP()
 */
final class OtpChannel extends Enum
{
    const MAIL = 'mail';
    const SMS = 'sms';
    const IN_APP = 'in_app';
}
