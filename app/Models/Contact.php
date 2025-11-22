<?php

namespace App\Models;

use App\Traits\General\CacheRouteBinding;
use App\Traits\General\CustomPagingSize;
use App\Traits\General\HasFilter;
use App\Traits\General\HasSearch;
use App\Traits\General\HasSort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasSearch,
        HasSort,
        CustomPagingSize,
        CacheRouteBinding,
        HasFactory,
        HasFilter;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $filterables = [
        'created_at',
        'user_id',
        'country_code',
    ];

    protected $searchables = [
        'name',
        'email',
        'phone_number',
        'country_code',
        'note',
    ];

    protected $sortables = [
        'created_at',
        'phone_number',
        'email',
        'country_code',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
