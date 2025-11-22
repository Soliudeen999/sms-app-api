<?php

namespace App\Models;

use App\Enums\Otp\OtpChannel;
use App\Enums\Otp\OtpType;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'channel' => OtpChannel::class,
            'type' => OtpType::class,
        ];
    }

    public function isExpired(): bool
    {
        if (! $this->expires_at) {
            return true;
        }

        return $this->expires_at?->isPast();
    }
}
