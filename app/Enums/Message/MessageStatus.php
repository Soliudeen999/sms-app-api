<?php

declare(strict_types=1);

namespace App\Enums\Message;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static FAILED()
 * @method static static DELIVERED()
 * @method static static QUEUED()
 * @method static static SENT()
 * @method static static EXPIRED()
 * @method static static ONGOING()
 */
final class MessageStatus extends Enum
{
    const PENDING = 'pending';
    const FAILED = 'failed';
    const DELIVERED = 'delivered';
    const QUEUED = 'queued';
    const SENT = 'sent';
    const EXPIRED = 'expired';
    const ONGOING = 'ongoing';
}
