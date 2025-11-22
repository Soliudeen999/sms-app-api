<?php

namespace App\Models;

use App\Enums\Otp\OtpChannel;
use App\Enums\Otp\OtpType;
use App\Enums\User\UserRole;
use App\Traits\General\HasOtp;
use App\Traits\General\HasSearch;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory,
        Notifiable,
        HasSearch,
        HasOtp,
        HasApiTokens;

    protected $guarded = ['id', 'updated_at', 'created_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class
        ];
    }


    public function sendEmailVerificationNotification()
    {
        if (! $this->hasVerifiedEmail()) {
            $this->sendNewOtp(OtpType::fromValue(OtpType::VERIFY), OtpChannel::fromValue(OtpChannel::MAIL));
        }
    }
}
