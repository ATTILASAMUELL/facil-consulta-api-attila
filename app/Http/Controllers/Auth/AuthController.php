<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\DTOs\Auth\CreateUserDTO;
use App\DTOs\Auth\UpdateUserDTO;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

/**
 * @group Auth
 *
 * API endpoints for user authentication, registration, and profile management.
 */
class AuthController extends Controller
{
    use ApiResponse;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: john.doe@example.com
     * @bodyParam password string required The password of the user. Example: secret123
     *
     * @response 201 {
     *   "message": "User registered successfully",
     *   "user": {"id":1, "name":"John Doe", "email":"john.doe@example.com"}
     * }
     * @response 400 {
     *   "error": "Failed to register user. Please try again later."
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $dto = CreateUserDTO::fromRequest($request->validated());
            $user = $this->authService->register($dto);
            return $this->successResponse(new UserResource($user), 'User registered successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse('Failed to register user. Please try again later.', 400);
        }
    }

    /**
     * Update an existing user.
     *
     * @bodyParam name string The new name of the user. Example: John Doe
     * @bodyParam email string The new email of the user. Example: john.new@example.com
     * @bodyParam password string The new password of the user. Example: newpassword123
     *
     * @response 200 {
     *   "message": "User updated successfully",
     *   "user": {"id":1, "name":"John Doe", "email":"john.doe@example.com"}
     * }
     * @response 400 {
     *   "error": "Failed to update user. Please try again later."
     * }
     */
    public function updateUser(Request $request, $id): JsonResponse
    {
        try {
            $dto = UpdateUserDTO::fromRequest($request->validated());
            $user = $this->authService->update($id, $dto);
            return $this->successResponse(new UserResource($user), 'User updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating user with ID ' . $id . ': ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse('Failed to update user. Please try again later.', 400);
        }
    }

    /**
     * Login a user and get a token.
     *
     * @bodyParam email string required The email of the user. Example: john.doe@example.com
     * @bodyParam password string required The password of the user. Example: secret123
     *
     * @response 200 {
     *   "message": "Login successful",
     *   "user": {"id":1, "name":"John Doe", "email":"john.doe@example.com"},
     *   "token": "jwt-token",
     *   "token_type": "Bearer",
     *   "expires_in": "2025-01-01T00:00:00"
     * }
     * @response 401 {
     *   "error": "Login failed. Please try again later."
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());

            return $this->successResponse([
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'token_type' => 'Bearer',
                'expires_in' => now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
            ], 'Login successful');
        } catch (\Exception $e) {
            Log::error('Error during login attempt: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse('Login failed. Please try again later.', 401, "Por favor tente novamente mais tarde.");
        }
    }

    /**
     * Logout the user and invalidate the token.
     *
     * @response 200 {
     *   "message": "Successfully logged out"
     * }
     * @response 400 {
     *   "error": "Failed to log out. Please try again later."
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout();
            return $this->successResponse([], 'Successfully logged out');
        } catch (\Exception $e) {
            Log::error('Error logging out: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse('Failed to log out. Please try again later.', 400);
        }
    }

    /**
     * Refresh the user token.
     *
     * @response 200 {
     *   "message": "Token refreshed successfully",
     *   "token": "new-jwt-token"
     * }
     * @response 400 {
     *   "error": "Failed to refresh token. Please try again later."
     * }
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $token = $this->authService->refresh();
            return $this->successResponse(['token' => $token], 'Token refreshed successfully');
        } catch (\Exception $e) {
            Log::error('Error refreshing token: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse('Failed to refresh token. Please try again later.', 400);
        }
    }

    /**
     * Get the authenticated user's profile.
     *
     * @response 200 {
     *   "message": "User profile retrieved successfully",
     *   "user": {"id":1, "name":"John Doe", "email":"john.doe@example.com"}
     * }
     * @response 400 {
     *   "error": "Failed to retrieve user profile. Please try again later."
     * }
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $user = $this->authService->profile();
            return $this->successResponse(new UserResource($user), 'User profile retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving user profile: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse('Failed to retrieve user profile. Please try again later.', 400);
        }
    }
}
