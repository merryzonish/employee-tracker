<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Get all branches, optionally filtered by company.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Branch::with(['company', 'country', 'state', 'city'])->orderBy('name');

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $branches = $query->get();

        return response()->json([
            'message' => 'Branches fetched successfully',
            'data'    => $branches,
        ]);
    }

    /**
     * Store a new branch.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'company_id'  => 'required|exists:companies,id',
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:50',
            'is_main'     => 'nullable|boolean',
            'type'        => 'nullable|string|max:100',
            'country_id'  => 'nullable|exists:countries,id',
            'state_id'    => 'nullable|exists:states,id',
            'city_id'     => 'nullable|exists:cities,id',
            'address'     => 'nullable|string',
            'postal_code' => 'nullable|string|max:50',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'fax'         => 'nullable|string|max:50',
            'status'      => 'nullable|in:active,inactive,closed',
            'notes'       => 'nullable|string',
        ]);

        $branch = Branch::create($request->only([
            'company_id', 'name', 'code', 'is_main', 'type', 'country_id',
            'state_id', 'city_id', 'address', 'postal_code', 'email',
            'phone', 'fax', 'status', 'notes',
        ]));

        return response()->json([
            'message' => 'Branch created successfully',
            'data'    => $branch,
        ], 201);
    }

    /**
     * Update an existing branch.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'company_id'  => 'nullable|exists:companies,id',
            'name'        => 'nullable|string|max:255',
            'code'        => 'nullable|string|max:50',
            'is_main'     => 'nullable|boolean',
            'type'        => 'nullable|string|max:100',
            'country_id'  => 'nullable|exists:countries,id',
            'state_id'    => 'nullable|exists:states,id',
            'city_id'     => 'nullable|exists:cities,id',
            'address'     => 'nullable|string',
            'postal_code' => 'nullable|string|max:50',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'fax'         => 'nullable|string|max:50',
            'status'      => 'nullable|in:active,inactive,closed',
            'notes'       => 'nullable|string',
        ]);

        $branch->update($request->only([
            'company_id', 'name', 'code', 'is_main', 'type', 'country_id',
            'state_id', 'city_id', 'address', 'postal_code', 'email',
            'phone', 'fax', 'status', 'notes',
        ]));

        return response()->json([
            'message' => 'Branch updated successfully',
            'data'    => $branch,
        ]);
    }

    /**
     * Delete a branch (soft delete).
     */
    public function destroy(int $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->json([
            'message' => 'Branch deleted successfully',
        ]);
    }
}