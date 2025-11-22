<?php

declare(strict_types=1);

namespace App\Traits\General;

use Exception;
use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsResponseCache
{
    public static function bootClearsResponseCache()
    {
        if (! method_exists(static::class, 'getModelGeneralCacheTag') || ! empty($tag)) {
            throw new Exception(class_basename(static::class).' must define a static getModelGeneralCacheTag method that returns array to use the ClearsResponseCache trait');
        }

        $tag = self::getModelGeneralCacheTag();

        if (! is_array($tag)) {
            throw new Exception('getModelGeneralCacheTag method must return an array');
        }

        self::saved(function () use ($tag): void {
            ResponseCache::clear($tag);
        });

        self::created(function () use ($tag): void {
            ResponseCache::clear($tag);
        });

        self::updated(function () use ($tag): void {
            ResponseCache::clear($tag);
        });

        self::deleted(function () use ($tag): void {
            ResponseCache::clear($tag);
        });
    }
}
