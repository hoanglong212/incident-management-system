<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $userRole = Role::where('name', 'USER')->first();

        if (!$userRole) {
            return response()->json([
                'message' => 'Default USER role not found. Please run seeders first.',
            ], 500);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role_id' => $userRole->id,
            'status' => 'ACTIVE',
        ]);

        $user->load('role');

        $tokenExpiresAt = now()->addDays(7);

        $token = $user->createToken(
            'api-token',
            $this->abilitiesForRole($user->role->name),
            $tokenExpiresAt
        )->plainTextToken;

        $this->writeAuditLog(
            user: $user,
            action: 'REGISTER',
            request: $request,
            newValue: [
                'email' => $user->email,
                'role' => $user->role->name,
            ]
        );

        return response()->json([
            'message' => 'User registered successfully',
            'token_type' => 'Bearer',
            'token' => $token,
            'expires_at' => $tokenExpiresAt->toDateTimeString(),
            'user' => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::with('role')
            ->where('email', strtolower($data['email']))
            ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            $this->writeAuditLog(
                user: $user,
                action: 'LOGIN_FAILED',
                request: $request,
                newValue: [
                    'email' => strtolower($data['email']),
                    'reason' => 'invalid_credentials',
                ]
            );

            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        if ($user->status !== 'ACTIVE') {
            $this->writeAuditLog(
                user: $user,
                action: 'LOGIN_FAILED',
                request: $request,
                newValue: [
                    'email' => $user->email,
                    'reason' => 'inactive_account',
                ]
            );

            return response()->json([
                'message' => 'Your account is inactive',
            ], 403);
        }

        // Chính sách hiện tại: mỗi user chỉ giữ 1 token active.
        $user->tokens()->delete();

        $tokenExpiresAt = now()->addDays(7);

        $token = $user->createToken(
            'api-token',
            $this->abilitiesForRole($user->role->name),
            $tokenExpiresAt
        )->plainTextToken;

        $this->writeAuditLog(
            user: $user,
            action: 'LOGIN_SUCCESS',
            request: $request,
            newValue: [
                'email' => $user->email,
                'role' => $user->role->name,
            ]
        );

        return response()->json([
            'message' => 'Login successfully',
            'token_type' => 'Bearer',
            'token' => $token,
            'expires_at' => $tokenExpiresAt->toDateTimeString(),
            'user' => new UserResource($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('role');

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user()->load('role');

        $this->writeAuditLog(
            user: $user,
            action: 'LOGOUT',
            request: $request,
            newValue: [
                'email' => $user->email,
            ]
        );

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successfully',
        ]);
    }

    private function abilitiesForRole(string $roleName): array
    {
        return match ($roleName) {
            'ADMIN' => [
                'incident:create',
                'incident:view',
                'incident:assign',
                'incident:update',
                'incident:delete',
                'category:manage',
                'user:manage',
                'dashboard:view',
                'report:view',
            ],

            'TECHNICIAN' => [
                'incident:view-assigned',
                'incident:update-status',
                'comment:create',
            ],

            'MANAGER' => [
                'incident:view',
                'dashboard:view',
                'report:view',
            ],

            default => [
                'incident:create',
                'incident:view-own',
                'comment:create',
            ],
        };
    }

    private function writeAuditLog(
        ?User $user,
        string $action,
        Request $request,
        array $newValue = [],
        ?array $oldValue = null
    ): void {
        try {
            AuditLog::create([
                'user_id' => $user?->id,
                'action' => $action,
                'entity_type' => 'AUTH',
                'entity_id' => $user?->id,
                'old_value' => $oldValue,
                'new_value' => $newValue ?: null,
                'ip_address' => $request->ip(),
            ]);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}