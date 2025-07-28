<?php

namespace App\Http\Controllers;

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
                    'created_at' => $media->created_at,
                ],
                'message' => 'File uploaded successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UPLOAD_ERROR',
                    'message' => 'File upload failed: ' . $e->getMessage(),
                ]
            ], 500);
        }
    }

    /**
     * Get media files
     */
    public function index(Request $request): JsonResponse
    {
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
                'created_at' => $item->created_at,
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
        $user = $request->user();
        $media = $user->getMedia()->where('id', $id)->first();

        if (!$media) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'MEDIA_NOT_FOUND',
                    'message' => 'Media file not found.',
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
                    'message' => 'Failed to delete media file: ' . $e->getMessage(),
                ]
            ], 500);
        }
    }
}
