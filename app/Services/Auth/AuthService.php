<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enums\Otp\OtpChannel;
use App\Enums\Otp\OtpType;
use App\Exceptions\DisplayableException;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Stevebauman\Location\Facades\Location;

class AuthService
{
    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(array $data): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($data['email']), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey($data['email']));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(string $key): string
    {
        return Str::transliterate(Str::lower($key) . '|' . request()->ip());
    }

    public function sendLoginOtp(array $data): void
    {
        $user = User::query()->where('email', $data['email'])->first();
        $user->sendNewOtp($data['otp_type'], $data['otp_channel']);
    }

    public function login(array $data): User
    {
        $this->ensureIsNotRateLimited($data);

        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password'] ?? ''])) {
            throw ValidationException::withMessages(['email' => 'The provided credentials are incorrect.']);
        }

        RateLimiter::clear($this->throttleKey($data['email']));

        $user = Auth::user();

        event(new Login('api', $user, false));

        $user->token = $user->createToken('API Token')->plainTextToken;

        return $user;
    }

    public function register(array $data): User
    {
        return DB::transaction(function () use ($data, &$user) {
            $user = User::create($data);

            Auth::login($user);

            $user->token = $user->createToken('API Token')->plainTextToken;

            event(new Registered($user));

            return $user;
        });
    }

    public function verifyEmail(User $user, array $otpData): void
    {
        $otp = $user->verifyOtp($otpData['otp'], OtpType::VERIFY, $otpData['otp_channel']);

        if (is_string($otp)) {
            $message = match ($otp) {
                'INVALID' => 'Invalid Otp',
                'EXPIRED' => 'Otp has expired',
                default => 'Invalid Otp'
            };

            throw ValidationException::withMessages(['otp' => $message]);
        }

        $user->markEmailAsVerified();
    }
}
