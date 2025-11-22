<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Str;

trait HasSpecialID
{
    /**
     * We can't be too careful to make sure things are as unique as we want.
     *
     * @param null|mixed $prefix
     */
    protected static function generateID(string $field_name, $prefix = null): string
    {
        $uuid = $prefix ?? '' . strtoupper(Str::random(10));

        if (static::where($field_name, $uuid)->exists()) {
            return static::generateID($field_name, $prefix);
        }

        return $uuid;
    }

    /**
     * Attach UUID to the just created data.
     */
    public static function attachSpecialId(string $field_name = 'special_id', ?string $prefix = null)
    {
        static::creating(function (self $model) use ($field_name): void {
            $model->{$field_name} = (string) static::generateID($field_name);
        });
    }
}
