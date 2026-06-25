<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Get all states, optionally filtered by country.
     */
    public function index(Request $request): JsonResponse
    {
        $query = State::with('country')->orderBy('name');

        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        $states = $query->get();

        return response()->json([
            'message' => 'States fetched successfully',
            'data'    => $states,
        ]);
    }

    /**
     * Store a new state.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name'       => 'required|string|max:255',
            'code'       => 'nullable|string|max:50',
        ]);

        $state = State::create($request->only(['country_id', 'name', 'code']));

        return response()->json([
            'message' => 'State created successfully',
            'data'    => $state,
        ], 201);
    }

    /**
     * Update an existing state.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $state = State::findOrFail($id);

        $request->validate([
            'country_id' => 'nullable|exists:countries,id',
            'name'       => 'nullable|string|max:255',
            'code'       => 'nullable|string|max:50',
        ]);

        $state->update($request->only(['country_id', 'name', 'code']));

        return response()->json([
            'message' => 'State updated successfully',
            'data'    => $state,
        ]);
    }

    /**
     * Delete a state.
     */
    public function destroy(int $id): JsonResponse
    {
        $state = State::findOrFail($id);
        $state->delete();

        return response()->json([
            'message' => 'State deleted successfully',
        ]);
    }
}