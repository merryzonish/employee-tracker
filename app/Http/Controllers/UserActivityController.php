<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserActivityController extends Controller
{
    /**
     * Store activity data sent by the desktop tracker.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'app_name'     => 'nullable|string|max:255',
            'window_title' => 'nullable|string|max:255',
            'clicks'       => 'nullable|integer|min:0',
            'keystrokes'   => 'nullable|integer|min:0',
            'is_idle'      => 'nullable|boolean',
            'tracked_at'   => 'required|date',
        ]);

        $activity = UserActivity::create([
            'user_id'      => $request->user()->id,
            'app_name'     => $request->app_name,
            'window_title' => $request->window_title,
            'clicks'       => $request->clicks ?? 0,
            'keystrokes'   => $request->keystrokes ?? 0,
            'is_idle'      => $request->is_idle ?? false,
            'tracked_at'   => $request->tracked_at,
        ]);

        return response()->json([
            'message' => 'Activity tracked successfully',
            'data'    => $activity,
        ], 201);
    }

    /**
     * Return activity stats for the authenticated user.
     */
    public function stats(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'nullable|date',
        ]);

        $query = UserActivity::where('user_id', $request->user()->id);

        if ($request->has('date')) {
            $query->whereDate('tracked_at', $request->date);
        }

        $activities = $query->orderBy('tracked_at', 'desc')->paginate(10);

        $summary = UserActivity::where('user_id', $request->user()->id)
            ->when($request->date, fn($q) => $q->whereDate('tracked_at', $request->date))
            ->selectRaw('
                SUM(clicks) as total_clicks,
                SUM(keystrokes) as total_keystrokes,
                SUM(CASE WHEN is_idle = 1 THEN 1 ELSE 0 END) as idle_count,
                SUM(CASE WHEN is_idle = 0 THEN 1 ELSE 0 END) as active_count
            ')
            ->first();

        return response()->json([
            'message'    => 'Activity stats fetched successfully',
            'summary'    => $summary,
            'activities' => $activities,
        ]);
    }
}