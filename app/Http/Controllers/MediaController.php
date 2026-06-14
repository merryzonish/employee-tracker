<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Get all media for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $media = Media::where('user_id', $request->user()->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'message' => 'Media fetched successfully',
            'data'    => $media,
        ]);
    }

    /**
     * Upload and store a new media file.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file'        => 'required|file|max:10240',
            'category_id' => 'nullable|exists:categories,id',
            'model_type'  => 'nullable|string',
            'model_id'    => 'nullable|integer',
        ]);

        $file     = $request->file('file');
        $user     = $request->user();
        $email    = $user->email;

        $directory = public_path("media/{$email}");
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($directory, $filename);
        $filePath = "media/{$email}/{$filename}";

        $media = Media::create([
            'user_id'     => $user->id,
            'file_path'   => $filePath,
            'category_id' => $request->category_id,
            'model_type'  => $request->model_type,
            'model_id'    => $request->model_id,
        ]);

        return response()->json([
            'message' => 'Media uploaded successfully',
            'data'    => $media,
        ], 201);
    }

    /**
     * Delete a media file.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $media = Media::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $filePath = public_path($media->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $media->delete();

        return response()->json([
            'message' => 'Media deleted successfully',
        ]);
    }
}