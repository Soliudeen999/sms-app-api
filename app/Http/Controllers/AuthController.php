<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Otp\OtpType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendVerificationOtpRequest;
use App\Http\Requests\Auth\SendLoginOtpRequest;
use App\Http\Requests\Auth\VerifyEmailOtpRequest;
use App\Http\Resources\User\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request->validated());

        return Response::success(UserResource::make($user));
    }

    public function loginWithOtp(SendLoginOtpRequest $request): JsonResponse
    {
        $this->authService->sendLoginOtp($request->validated());

        return Response::success(message: 'Login Otp sent successfully');
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        return Response::success(UserResource::make($user));
    }

    public function verifyEmail(VerifyEmailOtpRequest $request): JsonResponse
    {
        $user = $request->user();
        $this->authService->verifyEmail($user, $request->validated());

        return Response::success(UserResource::make($user), 'Email verified successfully');
    }

    public function resendVerificationCode(ResendVerificationOtpRequest $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return Response::success(['expires_at' => OtpType::fromValue('verify')->getExpiryTime()], 'Verification code sent successfully');
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('api')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Response::success(message: 'User logged out successfully');
    }
}
