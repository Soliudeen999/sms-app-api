<?php

declare(strict_types=1);

namespace App\Enums\Providers;

use BenSampo\Enum\Enum;

/**
 * @method static static DotGo()
 * @method static static T2frocoms()
 * @method static static AfricaIsTalking()
 */
final class SmsProviders extends Enum
{
    const DotGo = 'dotgo';
    const T2frocoms = 't2frocoms';
    const AfricaIsTalking = 'africa_is_talking';
}
