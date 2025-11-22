<?php

declare(strict_types=1);

namespace App\Traits\General;

use App\Enums\Otp\OtpChannel;
use App\Enums\Otp\OtpType;
use App\Models\Otp;
use App\Notifications\User\OtpNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOtp
{
    public function otps(): HasMany
    {
        return $this->hasMany(Otp::class);
    }

    public function latestOtp(): HasOne
    {
        return $this->otps()->latestOfMany();
    }

    public function generateNewOtp(OtpType $otpType, OtpChannel $otpChannel): Otp
    {
        $otpTTL = $otpType->getExpiryTime();

        return $this->otps()->create([
            'email' => $this->email ?? null,
            'type' => $otpType->value,
            'channel' => $otpChannel->value,
            'code' => random_int(100000, 999999),
            'expires_at' => now()->addMinutes($otpTTL),
        ]);
    }

    public function sendNewOtp(OtpType $otpType, OtpChannel $otpChannel): void
    {
        $otp = $this->generateNewOtp($otpType, $otpChannel);
        $this->notify(new OtpNotification($otp));
    }

    public function verifyOtp(string|int $code, ?string $otpType = null, ?string $otpChannel = null): Otp|string
    {
        $otpType ??= OtpType::GENERAL;
        $otpChannel ??= OtpChannel::MAIL;

        $otp = $this->otps()
            ->where('code', $code)
            ->where('type', $otpType)
            ->where('channel', $otpChannel)
            ->latest()
            ->first();

        if (! $otp) {
            // throw exception
            return 'INVALID';
        }

        if ($otp->isExpired()) {
            return 'EXPIRED';
        }

        return $otp;
    }
}
