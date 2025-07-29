<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class SearchController extends Controller
{
    /**
     * Search content
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:1|max:255',
            'limit' => 'sometimes|integer|min:1|max:100',
            'offset' => 'sometimes|integer|min:0',
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

        $query = $request->input('q');
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);

        try {
            // Search users by name or email
            $users = User::search($query)
                ->take($limit)
                ->skip($offset)
                ->get();

            $results = $users->map(function ($user) {
                return [
                    'type' => 'user',
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'results' => $results,
                    'query' => $query,
                    'limit' => $limit,
                    'offset' => $offset,
                    'total' => $results->count(),
                ],
                'message' => 'Search completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'SEARCH_ERROR',
                    'message' => 'Search failed: ' . $e->getMessage(),
                ]
            ], 500);
        }
    }
}
