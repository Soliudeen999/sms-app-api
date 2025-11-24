<?php

declare(strict_types=1);

namespace App\Traits\General;

use App\Exceptions\InvalidQueryParameter;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasFilter
{
    private array $allowedComparisonOperators = ['gt', 'lt', 'eq', 'btw', 'in', 'neq', 'gte', 'lte'];

    private array $dbConnectionsThatSupportsWhereJsonContains = [
        'mysql',
        'mariadb',
        'pgsql'
    ];

    public function scopeFilter(Builder $query, ?array $filters = null): Builder
    {
        if (! property_exists($this, 'filterables')) {
            throw new Exception('$filterables property not defined in this model');
        }

        /**
         * Pick the request parameters that only intersects with the filterables properties specified
         * usign the **keys and values** of the filterables
         */
        $filters ??= request()->only(
            array_merge(
                array_intersect(array_keys(request()->query()), $this->filterables),
                array_intersect(array_keys(request()->query()), array_keys($this->filterables))
            )
        );

        if (! empty($this->filterables) && ! empty($filters)) {
            foreach ($filters as $column => $value) {

                if (in_array($column, $this->filterables) || in_array($column, array_keys($this->filterables))) {

                    if (is_array($value)) {
                        foreach ($value as $key => $question) {
                            if (in_array($key, $this->allowedComparisonOperators)) {
                                $query = $this->buildQuery($query, $column, $key, $question);
                            }
                        }

                        continue;
                    }

                    /**
                     * Check if the filterable column is defined to use special keys for values.
                     */
                    if (isset($this->filterables[$column]) && is_array($this->filterables[$column])) {
                        // Check if value is available as speicified in the model
                        if (in_array($value, $this->filterables[$column])) {
                            if ($column == 'withTrashed') {
                                if (in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, get_declared_traits())) {
                                    $value == 'with'
                                        ? $query->withTrashed()
                                        : $query->onlyTrashed();
                                }

                                continue;
                            } else {
                                $query->where($column, $value);

                                continue;
                            }
                        }

                        $valids = implode(',', $this->filterables[$column]);

                        throw new InvalidQueryParameter("Invalid column {$column} value specified in query parameter. Valid values are {$valids}");
                    }

                    // Check if its a relationship
                    if (isset($this->filterables[$column]) && is_string($this->filterables[$column])) {

                        $setterString = $this->filterables[$column];

                        $setterString = str_replace([':', ','], '*', $setterString);

                        $related = explode('*', $this->filterables[$column]);

                        $modelName = $this::class;

                        /**
                         * This may be json field filtering
                         */
                        if (count($related) >= 2 && $related[0] == 'json') {
                            $connectionName = DB::connection()->getDriverName();

                            if (!in_array($connectionName, $this->dbConnectionsThatSupportsWhereJsonContains)) {
                                throw new Exception("WhereJsonContains is not supported by the current database driver. Current driver: {$connectionName}");
                            }

                            $fields = array_shift($related);
                            $arrangedField = implode('->', $fields);
                            $query->whereJsonContains($arrangedField, $value);

                            continue;
                        }

                        if (count($related) !== 3) {
                            throw new Exception("Invalid relationship filter configuration for column {$column} in model {$modelName}");
                        }

                        [$table, $relationship, $column] = $related;

                        if (! method_exists($this, $relationship)) {
                            throw new Exception("Invalid Model Relationship {$relationship} specified for column {$column} in model {$modelName}");
                        }

                        $query->whereHas($relationship, fn($query) => $query->where($table . $column, $value));
                    }

                    $query->where($column, $value);
                }
            }
        }

        return $query;
    }

    public function dataIsArray(string|array $data): bool
    {
        $array = json_decode($data, true);

        return is_array($array) && json_last_error() === JSON_ERROR_NONE;
    }

    private function buildQuery(Builder $query, string $field, string $operator, $data): Builder
    {
        $operator = match ($operator) {
            'btw' => 'between',
            'in' => 'in',
            'gt' => '>',
            'lt' => '<',
            'lte' => '<=',
            'gte' => '>=',
            'neq' => '<>',
            'eq' => '=',
        };

        if ($data && $this->dataIsArray($data)) {
            $data = json_decode((string) $data);
        }

        if (is_array($data) && count($data) !== 2) {
            return $query;
        }

        $valueToCheck = is_array($data) ? $data[0] : $data;

        if (strtotime((string) $valueToCheck)) {
            return $operator === 'between'
                ? $query->whereBetween($field, [Carbon::parse($data[0])->startOfDay(), Carbon::parse($data[1])->endOfDay()])
                : $query->whereDate($field, $operator, $data);
        }

        return $operator === 'between'
            ? $query->whereBetween($field, $data)
            : $query->where($field, $operator, $data);
    }
}
