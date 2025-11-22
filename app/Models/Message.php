<?php

namespace App\Models;

use App\Traits\General\CacheRouteBinding;
use App\Traits\General\CustomPagingSize;
use App\Traits\General\HasFilter;
use App\Traits\General\HasSearch;
use App\Traits\General\HasSort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasSearch,
        HasSort,
        CustomPagingSize,
        CacheRouteBinding,
        HasFilter;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $filterables = [
        'created_at',
        'user_id',
        'campaign_id',
        'contact_id',
        'type',
        'status',
        'status_changed_at',
    ];

    protected $searchables = [
        'title',
        'body',
        'type',
        'status',
    ];

    protected $sortables = [
        'created_at',
        'type',
        'status',
        'status_changed_at',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
