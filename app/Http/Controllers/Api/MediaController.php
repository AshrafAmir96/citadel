<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    /**
     * Upload file
     */
    public function store(Request $request): JsonResponse
    {
        // Check if user has permission to upload media
        if (!$request->user()->can('media.upload')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to upload media.',
                ]
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB max
            'collection' => 'sometimes|string|max:255',
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

        $user = $request->user();
        $collection = $request->input('collection', 'default');

        try {
            $media = $user
                ->addMediaFromRequest('file')
                ->toMediaCollection($collection);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                    'collection_name' => $media->collection_name,
                    'url' => $media->getUrl(),
                ],
                'message' => 'File uploaded successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UPLOAD_ERROR',
                    'message' => 'Failed to upload file.',
                    'details' => $e->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Get user's media files
     */
    public function index(Request $request): JsonResponse
    {
        // Check if user has permission to view media
        if (!$request->user()->can('media.view')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to view media.',
                ]
            ], 403);
        }

        $user = $request->user();
        $media = $user->getMedia();

        $mediaData = $media->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'file_name' => $item->file_name,
                'mime_type' => $item->mime_type,
                'size' => $item->size,
                'collection_name' => $item->collection_name,
                'url' => $item->getUrl(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $mediaData,
            'message' => 'Media files retrieved successfully'
        ]);
    }

    /**
     * Delete media file
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        // Check if user has permission to delete media
        if (!$request->user()->can('media.delete')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to delete media.',
                ]
            ], 403);
        }

        $media = Media::find($id);

        if (!$media || $media->model_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'MEDIA_NOT_FOUND',
                    'message' => 'Media file not found or you do not have permission to delete it.',
                ]
            ], 404);
        }

        try {
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media file deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'DELETE_ERROR',
                    'message' => 'Failed to delete media file.',
                    'details' => $e->getMessage()
                ]
            ], 500);
        }
    }
}
