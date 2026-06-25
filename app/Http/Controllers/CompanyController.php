<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Get all companies.
     */
    public function index(): JsonResponse
    {
        $companies = Company::with('country')->orderBy('name')->get();

        return response()->json([
            'message' => 'Companies fetched successfully',
            'data'    => $companies,
        ]);
    }

    /**
     * Store a new company.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'legal_name'      => 'nullable|string|max:255',
            'registration_no' => 'nullable|string|max:255',
            'tax_no'          => 'nullable|string|max:255',
            'country_id'      => 'nullable|exists:countries,id',
            'timezone'        => 'nullable|string|max:100',
            'currency'        => 'nullable|string|max:10',
            'language'        => 'nullable|string|max:10',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'website'         => 'nullable|string|max:255',
            'address'         => 'nullable|string',
            'postal_code'     => 'nullable|string|max:50',
            'is_active'       => 'nullable|boolean',
        ]);

        $company = Company::create($request->only([
            'name', 'legal_name', 'registration_no', 'tax_no', 'country_id',
            'timezone', 'currency', 'language', 'email', 'phone', 'website',
            'address', 'postal_code', 'is_active',
        ]));

        return response()->json([
            'message' => 'Company created successfully',
            'data'    => $company,
        ], 201);
    }

    /**
     * Update an existing company.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'name'            => 'nullable|string|max:255',
            'legal_name'      => 'nullable|string|max:255',
            'registration_no' => 'nullable|string|max:255',
            'tax_no'          => 'nullable|string|max:255',
            'country_id'      => 'nullable|exists:countries,id',
            'timezone'        => 'nullable|string|max:100',
            'currency'        => 'nullable|string|max:10',
            'language'        => 'nullable|string|max:10',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'website'         => 'nullable|string|max:255',
            'address'         => 'nullable|string',
            'postal_code'     => 'nullable|string|max:50',
            'is_active'       => 'nullable|boolean',
        ]);

        $company->update($request->only([
            'name', 'legal_name', 'registration_no', 'tax_no', 'country_id',
            'timezone', 'currency', 'language', 'email', 'phone', 'website',
            'address', 'postal_code', 'is_active',
        ]));

        return response()->json([
            'message' => 'Company updated successfully',
            'data'    => $company,
        ]);
    }

    /**
     * Delete a company (soft delete).
     */
    public function destroy(int $id): JsonResponse
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully',
        ]);
    }
}