<?php

declare(strict_types=1);

namespace App\Enums\Message;

use BenSampo\Enum\Enum;

/**
 * @method static static INSTANT()
 * @method static static SCHEDULED()
 * @method static static RECURING()
 */
final class MessageType extends Enum
{
    const INSTANT = 'instant';
    const SCHEDULED = 'scheduled';
    const RECURING = 'recurring';
}
