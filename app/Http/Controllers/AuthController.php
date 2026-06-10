<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle user login and return Sanctum token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'    => 'Login successful',
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => $user,
        ]);
    }

    /**
     * Handle user logout with restriction check.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        // Check global logout restriction
        $globalRestriction = Config::getValue('tracker_logout_restriction');

        if ($globalRestriction === true) {
            if (!$user->can_user_logout) {
                return response()->json([
                    'message' => 'Logout is restricted for your account.',
                ], 403);
            }
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}