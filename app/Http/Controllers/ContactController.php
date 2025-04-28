<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all();

        return response()->json($contacts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'email|max:255|unique:contacts,email',
            'phone' => 'required|string|unique:contacts,phone',
        ]);

        if (Contact::where('phone', $request->phone)->exists()) {
            return response()->json([
                'message' => 'The phone number has already been taken.',
                'errors' => ['phone' => ['This phone number is already in use.']]
            ], 422);
        }

        // Check if email exists (only if email was provided)
        if ($request->has('email') && $request->email && Contact::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'The email has already been taken.',
                'errors' => ['email' => ['This email is already in use.']]
            ], 422);
        }

        $contact = Contact::create($request->all());

        return response()->json($contact, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        try {
            // Check if contact exists (implicitly handled by route model binding)
            if (!$contact->exists) {
                return response()->json([
                    'message' => 'Contact not found',
                    'errors' => ['contact' => ['The specified contact does not exist']]
                ], 404);
            }

            $contact->delete();

            return response()->json([
                'message' => 'Contact deleted successfully',
                'data' => [
                    'id' => $contact->id,
                    'deleted_at' => now()->toDateTimeString()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete contact',
                'errors' => ['server' => ['An unexpected error occurred']]
            ], 500);
        }
    }
}
