<?php

namespace App\Http\Controllers;

use App\Models\UserContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserContactController extends Controller
{
    /**
     * Get all contacts for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $contacts = UserContact::where('user_id', $request->user()->id)
            ->orderBy('type')
            ->orderBy('is_primary', 'desc')
            ->get();

        return response()->json([
            'message' => 'Contacts fetched successfully',
            'data'    => $contacts,
        ]);
    }

    /**
     * Store a new contact for the authenticated user.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type'       => 'required|string|in:' . implode(',', UserContact::TYPES),
            'label'      => 'nullable|string|max:100',
            'value'      => 'required|string',
            'is_primary' => 'nullable|boolean',
            'notes'      => 'nullable|string',
        ]);

        $contact = UserContact::create([
            'user_id'    => $request->user()->id,
            'type'       => $request->type,
            'label'      => $request->label,
            'value'      => $request->value,
            'is_primary' => $request->is_primary ?? false,
            'notes'      => $request->notes,
        ]);

        return response()->json([
            'message' => 'Contact created successfully',
            'data'    => $contact,
        ], 201);
    }

    /**
     * Update an existing contact.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $contact = UserContact::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $request->validate([
            'type'       => 'nullable|string|in:' . implode(',', UserContact::TYPES),
            'label'      => 'nullable|string|max:100',
            'value'      => 'nullable|string',
            'is_primary' => 'nullable|boolean',
            'notes'      => 'nullable|string',
        ]);

        $contact->update($request->only([
            'type',
            'label',
            'value',
            'is_primary',
            'notes',
        ]));

        return response()->json([
            'message' => 'Contact updated successfully',
            'data'    => $contact,
        ]);
    }

    /**
     * Delete a contact.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $contact = UserContact::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully',
        ]);
    }
}