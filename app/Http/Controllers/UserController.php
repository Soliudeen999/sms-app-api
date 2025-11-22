<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(): JsonResponse
    {
        return Response::success(UserResource::make(auth_user()), 'User retrieved successfully');
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $this->userService->updateUser(auth_user(), $request->validated());
        return Response::success(UserResource::make($user), 'User updated successfully');
    }

    public function logout(): JsonResponse
    {
        auth_user()->tokens()->delete();
        return Response::success(message: 'Logged out successfully');
    }
}
