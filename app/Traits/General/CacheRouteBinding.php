<?php

declare(strict_types=1);

namespace App\Traits\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

trait CacheRouteBinding
{
    /**
     * Cache duration in minutes.
     */
    protected static $routeBindingCacheDuration = null;

    /**
     * Get cache duration.
     */
    protected function getRouteBindingCacheDuration(): int
    {
        return static::$routeBindingCacheDuration ?? config('app.routeBindingCacheDuration') ?? 60;
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param null|mixed $field
     */
    public function resolveRouteBinding(mixed $value, $field = null)
    {
        $field ??= $this->getRouteKeyName();

        // Generate cache key
        $cacheKey = static::class . ":{$field}:{$value}";

        // Try to get from cache first
        $model = Cache::get($cacheKey);

        if (! $model) {
            // If not in cache, get from database
            $model = $this->where($field, $value)
                ->when(method_exists($this, 'routeBindingQuery'), fn($query) => $this->routeBindingQuery($query))
                ->first();

            if ($model) {
                $mainTag = $this->mainTag($model);
                if (Cache::supportsTags())
                    Cache::tags($mainTag)->put($cacheKey, $model, $this->getRouteBindingCacheDuration());

                Cache::put($cacheKey, $model, $this->getRouteBindingCacheDuration());
            } else {
                throw new ModelNotFoundException;
            }
        }

        return $model;
    }

    public function mainTag(Model $model): string
    {
        return static::class . $model->id;
    }

    /**
     * Boot the trait.
     */
    public static function bootCacheRouteBinding()
    {
        static::saved(function ($model): void {
            $model->clearRouteBindingCache($model);
        });

        static::updated(function ($model): void {
            $model->clearRouteBindingCache($model);
        });

        static::deleted(function ($model): void {
            $model->clearRouteBindingCache($model);
        });
    }

    /**
     * Clear the route binding cache.
     */
    public function clearRouteBindingCache(mixed $model)
    {
        $mainTag = $this->mainTag($model);
        if (Cache::supportsTags())
            Cache::tags($mainTag)->flush();

        Cache::flush();
    }
}
