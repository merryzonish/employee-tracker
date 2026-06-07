<?php

namespace App\Http\Controllers;

use App\Models\UserScreenshot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class UserScreenshotController extends Controller
{
    /*
     * Handles automatic periodic screenshot uploads from the client application.
     * The client sends a screenshot file along with the timestamp of when it was taken.
     * The file is saved to public/screenshots/{user_email}/ directory.
     */
   public function store(Request $request)
{
    $request->validate([
        'screenshot'      => 'required|image|mimes:png,jpg,jpeg|max:5120',
        'screenshot_time' => 'required|date',
    ]);

    $user  = $request->user();
    $email = $user->email; 

    $file = $request->file('screenshot');

    $filePath = $file->store(
        "screenshots/{$email}",
        'public'
    );

    $screenshot = UserScreenshot::create([
        'user_id'         => $user->id,
        'file_path'       => $filePath,
        'screenshot_time' => $request->screenshot_time,
    ]);

    return response()->json([
        'message' => 'Screenshot uploaded successfully',
        'data'    => $screenshot,
    ], 201);
}

    /*
     * Handles real-time screenshot capture requests initiated by an admin.
     * Admin provides a user_id and the screenshot file captured from that user's screen.
     * The file is saved to public/screenshots/{user_email}/ directory.
     */
    public function capture(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'screenshot' => 'required|image|mimes:png,jpg,jpeg|max:5120',
        ]);

       $user  = User::findOrFail($request->user_id);
$email = $user->email;
$file  = $request->file('screenshot');

$filePath = $file->store(
    "screenshots/{$email}",
    'public'
);

$screenshot = UserScreenshot::create([
    'user_id'         => $user->id,
    'file_path'       => $filePath,
    'screenshot_time' => now(),
]);
        return response()->json([
            'message' => 'Real-time screenshot captured successfully',
            'data'    => $screenshot,
        ], 201);
    }

    /*
     * Returns paginated screenshot records for the authenticated user.
     * Supports optional date filtering via query parameter.
     * Results are sorted by screenshot_time in descending order.
     */
    public function index(Request $request)
    {
        $query = UserScreenshot::where('user_id', $request->user()->id)
            ->orderBy('screenshot_time', 'desc');

        if ($request->has('date')) {
            $query->whereDate('screenshot_time', $request->date);
        }

        $screenshots = $query->paginate(10);

        return response()->json([
            'message' => 'Screenshots fetched successfully',
            'data'    => $screenshots,
        ]);
    }
    /**
 * Deletes a screenshot record and its associated file.
 */
public function destroy(Request $request): JsonResponse
{
    $request->validate([
        'id' => 'required|integer|exists:user_screenshots,id',
    ]);

    $screenshot = UserScreenshot::where('id', $request->id)
        ->where('user_id', $request->user()->id)
        ->firstOrFail();

    // Delete file from storage
    $filePath = public_path($screenshot->file_path);
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $screenshot->delete();

    return response()->json([
        'message' => 'Screenshot deleted successfully',
    ]);
}
}