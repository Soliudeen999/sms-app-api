<?php

declare(strict_types=1);

namespace App\Traits\General;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    public function scopeSearch(Builder $query, $keyword = null): Builder
    {
        $keyword ??= request('search');

        if (is_null($keyword)) {
            return $query;
        }

        if (! property_exists($this, 'searchables')) {
            throw new Exception('Searchables property not defined in this model');
        }

        if (! empty($this->searchables)) {

            $searchables = $this->searchables;
        }
        // Making sure that the orWhere methods are inside one where
        $query->where(function ($query2) use ($keyword, $searchables): void {

            $firstQuery = $query2->where(array_pop($searchables), 'like', '%'.$keyword.'%');

            if (count($searchables) > 1) {

                foreach ($searchables as $column) {
                    $firstQuery->orWhere($column, 'like', '%'.$keyword.'%');
                }
            }
        });

        return $query;
    }
}
