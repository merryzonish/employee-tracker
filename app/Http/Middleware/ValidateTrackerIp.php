<?php

namespace App\Http\Middleware;

use App\Models\Config;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTrackerIp
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestIp = $request->ip();

        // Check global allowed IPs
        $globalAllowedIps = Config::getValue('tracker_allowed_ips');

        if (!empty($globalAllowedIps)) {
            if (!in_array($requestIp, $globalAllowedIps)) {
                return response()->json([
                    'message' => 'Access denied. Your IP is not in the global allowed list.',
                ], 403);
            }
        }

        // Check user-specific allowed IPs
        if ($request->user()) {
            $userAllowedIps = $request->user()->allowed_ips;

            if (!empty($userAllowedIps)) {
                if (!in_array($requestIp, $userAllowedIps)) {
                    return response()->json([
                        'message' => 'Access denied. Your IP is not in your allowed IP list.',
                    ], 403);
                }
            }
        }

        return $next($request);
    }
}