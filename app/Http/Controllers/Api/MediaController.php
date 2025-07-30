<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        if (! $request->user()->can('media.upload')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to upload media.',
                ],
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240|mimes:jpeg,jpg,png,gif,pdf,doc,docx,txt,zip,mp3,mp4,avi', // 10MB max with allowed types
            'collection' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'The given data was invalid.',
                    'details' => $validator->errors(),
                ],
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
                'message' => 'File uploaded successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UPLOAD_ERROR',
                    'message' => 'Failed to upload file.',
                    'details' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    /**
     * Get user's media files
     */
    public function index(Request $request): JsonResponse
    {
        // Check if user has permission to view media
        if (! $request->user()->can('media.view')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to view media.',
                ],
            ], 403);
        }

        $user = $request->user();

        // Get media query
        $mediaQuery = Media::where('model_id', $user->id)
            ->where('model_type', get_class($user));

        // Filter by collection if specified
        if ($request->has('collection')) {
            $mediaQuery->where('collection_name', $request->input('collection'));
        }

        // Pagination support
        $limit = $request->input('limit', 15);
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        $media = $mediaQuery->offset($offset)->limit($limit)->get();

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
            'message' => 'Media files retrieved successfully',
        ]);
    }

    /**
     * Delete media file
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        // Check if user has permission to delete media
        if (! $request->user()->can('media.delete')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to delete media.',
                ],
            ], 403);
        }

        $media = Media::find($id);

        if (! $media) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'MEDIA_NOT_FOUND',
                    'message' => 'Media file not found.',
                ],
            ], 404);
        }

        // Check if the media belongs to the current user
        if ((int) $media->model_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'You do not have permission to delete this media file.',
                ],
            ], 403);
        }

        try {
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media file deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'DELETE_ERROR',
                    'message' => 'Failed to delete media file.',
                    'details' => $e->getMessage(),
                ],
            ], 500);
        }
    }
}
