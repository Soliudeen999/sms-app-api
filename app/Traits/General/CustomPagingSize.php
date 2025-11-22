<?php

declare(strict_types=1);

namespace App\Traits\General;

use App\Exceptions\InvalidQueryParameter;

trait CustomPagingSize
{
    public function getPerPage()
    {
        $queryPageSize = collect(request()->only(['per_page', 'page_size', 'paginate']))
            ->filter()
            ->first();

        if ($queryPageSize) {
            if (! is_numeric($queryPageSize)) {
                throw new InvalidQueryParameter("Invalid query parameter for pagination size: {$queryPageSize}");
            }

            if ($queryPageSize > 100) {
                throw new InvalidQueryParameter('Invalid query parameter for pagination size value: max is 100');
            }

            return (int) $queryPageSize;
        }

        return $this->perPage;
    }
}
