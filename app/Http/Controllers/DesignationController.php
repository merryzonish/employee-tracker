<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /**
     * Get all designations, optionally filtered by company.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Designation::with('company')->orderBy('level');

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $designations = $query->get();

        return response()->json([
            'message' => 'Designations fetched successfully',
            'data'    => $designations,
        ]);
    }

    /**
     * Store a new designation.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name'       => 'required|string|max:255',
            'code'       => 'nullable|string|max:50',
            'level'      => 'nullable|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $designation = Designation::create($request->only([
            'company_id', 'name', 'code', 'level', 'notes',
        ]));

        return response()->json([
            'message' => 'Designation created successfully',
            'data'    => $designation,
        ], 201);
    }

    /**
     * Update an existing designation.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $designation = Designation::findOrFail($id);

        $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'name'       => 'nullable|string|max:255',
            'code'       => 'nullable|string|max:50',
            'level'      => 'nullable|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $designation->update($request->only([
            'company_id', 'name', 'code', 'level', 'notes',
        ]));

        return response()->json([
            'message' => 'Designation updated successfully',
            'data'    => $designation,
        ]);
    }

    /**
     * Delete a designation (soft delete).
     */
    public function destroy(int $id): JsonResponse
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        return response()->json([
            'message' => 'Designation deleted successfully',
        ]);
    }
}