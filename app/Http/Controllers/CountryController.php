<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Get all countries.
     */
    public function index(): JsonResponse
    {
        $countries = Country::orderBy('name')->get();

        return response()->json([
            'message' => 'Countries fetched successfully',
            'data'    => $countries,
        ]);
    }

    /**
     * Store a new country.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'iso2'       => 'required|string|size:2|unique:countries,iso2',
            'iso3'       => 'nullable|string|size:3',
            'phone_code' => 'nullable|string|max:10',
            'currency'   => 'nullable|string|max:10',
            'timezone'   => 'nullable|string|max:100',
        ]);

        $country = Country::create($request->only([
            'name', 'iso2', 'iso3', 'phone_code', 'currency', 'timezone',
        ]));

        return response()->json([
            'message' => 'Country created successfully',
            'data'    => $country,
        ], 201);
    }

    /**
     * Update an existing country.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $country = Country::findOrFail($id);

        $request->validate([
            'name'       => 'nullable|string|max:255',
            'iso2'       => 'nullable|string|size:2|unique:countries,iso2,' . $id,
            'iso3'       => 'nullable|string|size:3',
            'phone_code' => 'nullable|string|max:10',
            'currency'   => 'nullable|string|max:10',
            'timezone'   => 'nullable|string|max:100',
        ]);

        $country->update($request->only([
            'name', 'iso2', 'iso3', 'phone_code', 'currency', 'timezone',
        ]));

        return response()->json([
            'message' => 'Country updated successfully',
            'data'    => $country,
        ]);
    }

    /**
     * Delete a country.
     */
    public function destroy(int $id): JsonResponse
    {
        $country = Country::findOrFail($id);
        $country->delete();

        return response()->json([
            'message' => 'Country deleted successfully',
        ]);
    }
}