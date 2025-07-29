<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiDocumentationController extends Controller
{
    /**
     * Get API documentation information
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'title' => 'Citadel API',
                'version' => '1.0.0',
                'description' => 'Production-ready Laravel backend boilerplate API',
                'documentation_url' => url('/docs/api'),
                'endpoints' => [
                    'authentication' => [
                        'register' => 'POST /api/auth/register',
                        'login' => 'POST /api/auth/login',
                        'logout' => 'POST /api/auth/logout',
                        'user' => 'GET /api/user'
                    ],
                    'users' => [
                        'index' => 'GET /api/users',
                        'show' => 'GET /api/users/{id}',
                        'update' => 'PUT /api/users/{id}',
                        'assign_role' => 'POST /api/users/{id}/roles',
                        'permissions' => 'GET /api/users/{id}/permissions'
                    ],
                    'media' => [
                        'index' => 'GET /api/media',
                        'store' => 'POST /api/media',
                        'destroy' => 'DELETE /api/media/{id}'
                    ],
                    'search' => [
                        'search' => 'GET /api/search'
                    ]
                ]
            ],
            'message' => 'API documentation retrieved successfully'
        ]);
    }
}
