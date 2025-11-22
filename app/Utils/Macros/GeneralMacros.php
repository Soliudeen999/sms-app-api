<?php

declare(strict_types=1);

namespace App\Utils\Macros;

use BenSampo\Enum\Enum;

class GeneralMacros extends BaseMacro
{
    public static function getAsSeparatedString()
    {
        Enum::macro('getAsSeparatedString', function (string $separator = ',') {
            return implode($separator, self::getValues());
        });
    }
}
