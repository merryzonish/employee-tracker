<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Get all departments, optionally filtered by company or branch.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Department::with(['company', 'branch'])->orderBy('name');

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $departments = $query->get();

        return response()->json([
            'message' => 'Departments fetched successfully',
            'data'    => $departments,
        ]);
    }

    /**
     * Store a new department.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'branch_id'  => 'nullable|exists:branches,id',
            'name'       => 'required|string|max:255',
            'code'       => 'nullable|string|max:50',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:50',
            'notes'      => 'nullable|string',
        ]);

        $department = Department::create($request->only([
            'company_id', 'branch_id', 'name', 'code', 'email', 'phone', 'notes',
        ]));

        return response()->json([
            'message' => 'Department created successfully',
            'data'    => $department,
        ], 201);
    }

    /**
     * Update an existing department.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'branch_id'  => 'nullable|exists:branches,id',
            'name'       => 'nullable|string|max:255',
            'code'       => 'nullable|string|max:50',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:50',
            'notes'      => 'nullable|string',
        ]);

        $department->update($request->only([
            'company_id', 'branch_id', 'name', 'code', 'email', 'phone', 'notes',
        ]));

        return response()->json([
            'message' => 'Department updated successfully',
            'data'    => $department,
        ]);
    }

    /**
     * Delete a department (soft delete).
     */
    public function destroy(int $id): JsonResponse
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json([
            'message' => 'Department deleted successfully',
        ]);
    }
}