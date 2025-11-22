<?php

namespace App\Models;

use App\Enums\Campaign\CampaignRecipientType;
use App\Enums\Campaign\CampaignStatus;
use App\Enums\Message\MessageType;
use App\Traits\General\CacheRouteBinding;
use App\Traits\General\CustomPagingSize;
use App\Traits\General\HasFilter;
use App\Traits\General\HasSearch;
use App\Traits\General\HasSort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
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


    protected $hidden = [
        'provider',
    ];

    protected $filterables = [
        'created_at',
        'user_id',
        'type',
        'status',
    ];

    protected $searchables = [
        'title',
        'body',
        'status',
        'type',
        'recipient_type',
    ];

    protected $sortables = [
        'created_at',
        'status',
        'type',
        'last_processed_at',
    ];

    public function casts()
    {
        return [
            'recipient_type' => CampaignRecipientType::class,
            'type' => MessageType::class,
            'status' => CampaignStatus::class,
            'recurrence_config' => 'array',
            'scheduled_at' => 'datetime',
            'last_processed_at' => 'datetime',
            'extra_recipient_numbers' => 'array',
            'recipients' => 'array',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
