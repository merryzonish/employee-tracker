<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Get all cities, optionally filtered by state.
     */
    public function index(Request $request): JsonResponse
    {
        $query = City::with('state')->orderBy('name');

        if ($request->has('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        $cities = $query->get();

        return response()->json([
            'message' => 'Cities fetched successfully',
            'data'    => $cities,
        ]);
    }

    /**
     * Store a new city.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'state_id'  => 'required|exists:states,id',
            'name'      => 'required|string|max:255',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $city = City::create($request->only(['state_id', 'name', 'latitude', 'longitude']));

        return response()->json([
            'message' => 'City created successfully',
            'data'    => $city,
        ], 201);
    }

    /**
     * Update an existing city.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $city = City::findOrFail($id);

        $request->validate([
            'state_id'  => 'nullable|exists:states,id',
            'name'      => 'nullable|string|max:255',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $city->update($request->only(['state_id', 'name', 'latitude', 'longitude']));

        return response()->json([
            'message' => 'City updated successfully',
            'data'    => $city,
        ]);
    }

    /**
     * Delete a city.
     */
    public function destroy(int $id): JsonResponse
    {
        $city = City::findOrFail($id);
        $city->delete();

        return response()->json([
            'message' => 'City deleted successfully',
        ]);
    }
}