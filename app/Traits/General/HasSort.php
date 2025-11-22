<?php

declare(strict_types=1);

namespace App\Traits\General;

use App\Exceptions\InvalidQueryParameter;
use Exception;
use Illuminate\Database\Eloquent\Builder;

trait HasSort
{
    public bool $useLatestSortingDefaultly = true;

    private string $sortingKeyFromRequest = 'sort';

    private string $dataLimitKeyFromRequest = 'data_limit';

    /**
     * Validate if the field is sortable.
     *
     * @throws InvalidQueryParameter
     */
    protected function validateSortableField(string $field): void
    {
        if (! in_array($field, $this->sortables)) {
            throw new InvalidQueryParameter("Invalid field '{$field}' specified for sorting. Valid fields are: ".implode(', ', $this->sortables));
        }
    }

    /**
     * Validate the sort direction.
     *
     * @throws InvalidQueryParameter
     */
    protected function validateSortDirection(string $direction): void
    {
        if (! in_array(strtolower($direction), ['asc', 'desc', 'rand'])) {
            throw new InvalidQueryParameter("Invalid sort direction '{$direction}'. Valid directions are: asc, desc, rand");
        }
    }

    public function scopeSort(Builder $query, string|array $sorting = [], string $direction = 'desc'): Builder
    {
        $modelName = $this::class;

        if (! property_exists($this, 'sortables') || ! is_array($this->sortables)) {
            throw new Exception('$sortables property not defined or not as an array in this model : '.$modelName);
        }

        $sortingFromRequest = request()->query($this->sortingKeyFromRequest, []);

        if (is_string($sorting)) {
            $sorting = [$sorting => $direction];
        }

        $sorting = array_merge($sortingFromRequest, $sorting);

        if ($this->useLatestSortingDefaultly && ! in_array('created_at', array_keys($sorting))) {
            $query->latest();
        }

        foreach ($sorting as $field => $dir) {
            // If numeric key is provided, assume $dir is actually the field name
            // and use the default direction
            if (is_numeric($field)) {
                $field = $dir;
                $dir = $direction;
            }

            $this->validateSortableField($field);
            $this->validateSortDirection($dir);

            if ($dir == 'rand') {
                $query->orderByRaw('RAND()');
            } else {
                $query->orderBy($field, $dir);
            }

        }

        $limitSortValue = request()->query($this->dataLimitKeyFromRequest);

        if ($limitSortValue) {
            if (! is_numeric($limitSortValue)) {
                throw new InvalidQueryParameter("Invalid Query Parameter {$this->dataLimitKeyFromRequest}: Data Limit value must be a number.");
            }

            if (
                $limitSortValue > $max = config('system.max_data_limit_for_query') ||
                $limitSortValue < 1
            ) {
                throw new InvalidQueryParameter("Data limit invalid. Maximum allowed is {$max} and Minimun is 1");
            }

            $query->limit($limitSortValue);
        }

        return $query;
    }
}
