<?php

declare(strict_types=1);

namespace App\Traits\General;

use Illuminate\Support\Facades\DB;

trait HandleJsonColumns
{
    protected $pendingJsonOperations = [];

    /**
     * Process JSON operations for a column.
     */
    protected function processJsonOperations(mixed $column, mixed $operations)
    {
        $cases = [];

        foreach ($operations as $path => $operation) {
            $cases[] = match (true) {
                is_numeric($operation) => $this->buildNumericOperation($column, $path, $operation),
                is_array($operation) && isset($operation['increment']) => $this->buildNumericOperation($column, $path, $operation['increment']),
                is_array($operation) && isset($operation['append']) => $this->buildArrayAppendOperation($column, $path, $operation['append']),
                is_array($operation) && isset($operation['remove']) => $this->buildArrayRemoveOperation($column, $path, $operation['remove']),
                str_contains((string) $path, '.') => $this->buildNestedUpdate($column, $path, $operation),
                default => $this->buildSimpleUpdate($column, $path, $operation)
            };
        }

        return DB::raw(implode(' ', $cases));
    }

    protected function buildNumericOperation($column, $path, $value)
    {
        $path = str_replace('.', '->', $path);
        $operator = $value >= 0 ? '+' : '';

        return "JSON_SET({$column}, '$.{$path}',
            CAST(COALESCE(JSON_EXTRACT({$column}, '$.{$path}'), 0) AS SIGNED) {$operator}{$value})";
    }

    protected function buildArrayAppendOperation($column, $path, $value)
    {
        $path = str_replace('.', '->', $path);
        $jsonValue = json_encode($value);

        return "JSON_ARRAY_APPEND({$column}, '$.{$path}', {$jsonValue})";
    }

    protected function buildArrayRemoveOperation($column, $path, $index)
    {
        $path = str_replace('.', '->', $path);

        return "JSON_REMOVE({$column}, '$.{$path}[{$index}]')";
    }

    protected function buildNestedUpdate($column, $path, $value)
    {
        $path = str_replace('.', '->', $path);
        $jsonValue = json_encode($value);

        return "JSON_SET({$column}, '$.{$path}', {$jsonValue})";
    }

    protected function buildSimpleUpdate($column, $path, $value)
    {
        $jsonValue = json_encode($value);

        return "JSON_SET({$column}, '$.{$path}', {$jsonValue})";
    }

    /**
     * Queue JSON column operations.
     *
     * @param array|string $columns    Single column name or array of ['column' => [operations]]
     * @param array|null   $operations Operations array if $columns is string
     *
     * @return $this
     */
    public function updatingJsonColumn($columns, ?array $operations = null)
    {
        // Handle single column case
        if (is_string($columns)) {
            $this->pendingJsonOperations[] = [
                'column' => $columns,
                'operations' => $operations,
            ];

            return $this;
        }

        // Handle multiple columns
        foreach ($columns as $column => $ops) {
            $this->pendingJsonOperations[] = [
                'column' => $column,
                'operations' => $ops,
            ];
        }

        return $this;
    }

    /**
     * Update the model in the database.
     *
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        if (empty($this->pendingJsonOperations)) {
            return parent::update($attributes, $options); // No pending operations, proceed as usual
        }

        // Process all pending JSON operations
        foreach ($this->pendingJsonOperations as $operation) {
            $column = $operation['column'];
            $operations = $operation['operations'];

            $attributes[$column] = $this->processJsonOperations($column, $operations);
        }

        return parent::update($attributes, $operations);
        // $updated = $this->performUpdate($attributes);

        // if ($updated) {
        //     $this->fireModelEvent('updated', false);
        //     $this->pendingJsonOperations = []; // Clear pending operations
        // }

        // return $updated;
    }

    // protected function performUpdate(array $attributes)
    // {
    //     return $this->newQueryWithoutScopes()
    //         ->where($this->getKeyName(), $this->getKey())
    //         ->update($attributes);
    // }
}

/**
 * Usage of this trait.
 */

/**
 * Single column update.
 */

// $user->updatingJsonColumn('privileges', [
//     'view_greetings' => true,
//     'bulk_question' => false
// ])->update([
//     'name' => 'Sade'
// ]);

/**
 * Multiple column update.
 */

// $user->updatingJsonColumn([
//     'privileges' => [
//         'view_greetings' => true,
//         'bulk_question.doable' => -2
//     ],
//     'metrics' => [
//         'points' => +5,
//         'views' => ['increment' => 1]
//     ],
//     'settings' => [
//         'notifications.email' => true,
//         'preferences' => ['theme' => 'dark']
//     ]
// ])->update([
//     'status' => 'active'
// ]);

// // Array operations
// $user->updatingJsonColumn('data', [
//     'tags' => ['append' => 'new-tag'],
//     'removed_tags' => ['remove' => 0] // Remove first element
// ])->update();

// // Complex nested updates
// $user->updatingJsonColumn([
//     'settings' => [
//         'notifications.count' => +1,
//         'preferences.theme' => 'dark',
//         'limits.daily' => ['increment' => -2]
//     ],
//     'stats' => [
//         'visits' => ['increment' => 1],
//         'history' => ['append' => ['date' => now(), 'action' => 'login']]
//     ]
// ])->update();
