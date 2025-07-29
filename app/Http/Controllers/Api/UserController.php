<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Get all users (Admin only)
     */
    public function index(Request $request): JsonResponse
    {
        // Check if user has permission to view users
        if (!$request->user()->can('manage users')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to access this resource.',
                ]
            ], 403);
        }

        $users = User::paginate(15);

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Users retrieved successfully'
        ]);
    }

    /**
     * Get user by ID
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found.',
                ]
            ], 404);
        }

        // Users can only view their own profile unless they have admin permissions
        if ($request->user()->id !== $user->id && !$request->user()->can('manage users')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to access this resource.',
                ]
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User retrieved successfully'
        ]);
    }

    /**
     * Update user profile
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found.',
                ]
            ], 404);
        }

        // Users can only update their own profile unless they have admin permissions
        if ($request->user()->id !== $user->id && !$request->user()->can('manage users')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to access this resource.',
                ]
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'The given data was invalid.',
                    'details' => $validator->errors()
                ]
            ], 422);
        }

        $user->update($request->only(['name', 'email']));

        return response()->json([
            'success' => true,
            'data' => $user->fresh(),
            'message' => 'User updated successfully'
        ]);
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, int $id): JsonResponse
    {
        // Check if user has permission to manage roles
        if (!$request->user()->can('manage users')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to access this resource.',
                ]
            ], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found.',
                ]
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'The given data was invalid.',
                    'details' => $validator->errors()
                ]
            ], 422);
        }

        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'roles' => $user->roles->pluck('name')
            ],
            'message' => 'Role assigned successfully'
        ]);
    }

    /**
     * Get user permissions
     */
    public function permissions(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found.',
                ]
            ], 404);
        }

        // Users can only view their own permissions unless they have admin permissions
        if ($request->user()->id !== $user->id && !$request->user()->can('manage users')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to access this resource.',
                ]
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'direct_permissions' => $user->getDirectPermissions()->pluck('name')
            ],
            'message' => 'User permissions retrieved successfully'
        ]);
    }
}
