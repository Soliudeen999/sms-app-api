<?php

declare(strict_types=1);

namespace App\Enums\Message;

use BenSampo\Enum\Enum;

/**
 * @method static static DAILY()
 * @method static static WEEKLY()
 * @method static static MONTHLY()
 */
final class MessageRecurrence extends Enum
{
    const DAILY = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
}
