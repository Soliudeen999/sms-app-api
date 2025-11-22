<?php

declare(strict_types=1);

namespace App\Enums\Campaign;

use BenSampo\Enum\Enum;

/**
 * @method static static CONTACT_IDS()
 * @method static static PHONE_NUMBERS()
 * @method static static CONTACT_GROUPS()
 */
final class CampaignRecipientType extends Enum
{
    const CONTACT_IDS = 'contact_ids';
    const PHONE_NUMBERS = 'phone_numbers';
    const CONTACT_GROUPS = 'contact_groups';
}
