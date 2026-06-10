<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * List all configurations.
     */
    public function index(): JsonResponse
    {
        $configs = Config::all()->mapWithKeys(function ($config) {
            return [$config->key => $config->value];
        });

        return response()->json([
            'message' => 'Configurations fetched successfully',
            'data'    => $configs,
        ]);
    }

    /**
     * Create a new configuration.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'key'   => 'required|string|in:' . implode(',', Config::KEYS) . '|unique:configs,key',
            'value' => 'required',
        ]);

        $config = Config::create([
            'key'   => $request->key,
            'value' => $request->value,
        ]);

        return response()->json([
            'message' => 'Configuration created successfully',
            'data'    => $config,
        ], 201);
    }

    /**
     * Update an existing configuration by key.
     */
    public function update(Request $request, string $key): JsonResponse
    {
        $request->validate([
            'value' => 'required',
        ]);

        $config = Config::where('key', $key)->firstOrFail();

        $config->update([
            'value' => $request->value,
        ]);

        return response()->json([
            'message' => 'Configuration updated successfully',
            'data'    => $config,
        ]);
    }
}