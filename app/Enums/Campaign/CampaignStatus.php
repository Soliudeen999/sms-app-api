<?php

declare(strict_types=1);

namespace App\Enums\Campaign;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static FAILED()
 * @method static static COMPLETED()
 * @method static static QUEUED()
 * @method static static PAUSED()
 * @method static static ACTIVE()
 * @method static static PROCESSING()
 */
final class CampaignStatus extends Enum
{
    const PENDING = 'pending';
    const FAILED = 'failed';
    const COMPLETED = 'completed';
    const QUEUED = 'queued';
    const PAUSED = 'paused';
    const ACTIVE = 'active';
    const PROCESSING = 'processing';
}
