<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Response;

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
    // Validate request
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string',
        'description' => 'nullable|string|max:1000',
    ]);

    try {
        DB::beginTransaction();

        // Rate limiting by phone (3 requests per 30 mins)
        $phoneKey = 'contact_submissions_phone:' . $validated['phone'];
        if (RateLimiter::tooManyAttempts($phoneKey, 3)) {
            $retryAfter = RateLimiter::availableIn($phoneKey);
            return response()->json([
                'message' => 'Too many requests for this phone number.',
                'errors' => ['phone' => ["Please try again in {$retryAfter} seconds."]],
                'retry_after' => $retryAfter,
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }
        RateLimiter::hit($phoneKey, 1800); // 30 minutes

        // Rate limiting by email (3 requests per 30 mins)
        $emailKey = 'contact_submissions_email:' . $validated['email'];
        if (RateLimiter::tooManyAttempts($emailKey, 3)) {
            $retryAfter = RateLimiter::availableIn($emailKey);
            return response()->json([
                'message' => 'Too many requests for this email.',
                'errors' => ['email' => ["Please try again in {$retryAfter} seconds."]],
                'retry_after' => $retryAfter,
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }
        RateLimiter::hit($emailKey, 1800); // 30 minutes

        // Create contact
        $contact = Contact::create($validated);
        DB::commit();

        return response()->json($contact, Response::HTTP_CREATED);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to process your request.',
            'error' => $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
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
