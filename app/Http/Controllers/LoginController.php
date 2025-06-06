<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoginController extends Controller
{
    // Token expiration time in minutes (configurable)
    protected $tokenExpiration = 360; // 6 hours

    public function login(Request $request)
    {
        // 1. Rate Limiting (5 attempts per minute per IP/email)
        $maxAttempts = 5;
        $decayMinutes = 1; // Lock for 1 minute after 5 attempts

        $throttleKey = 'login:' . $request->ip() . '|' . $request->email;

        // Check if the user is rate-limited
        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => [
                    'Too many login attempts. Please try again in ' . $seconds . ' seconds.'
                ],
            ])->status(429); // HTTP 429 = Too Many Requests
        }

        // 2. Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 3. Find user (fail early if not found)
        $user = User::where('email', $request->email)->first();

        // 4. Verify credentials (increment rate limit on failure)
        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, $decayMinutes * 60); // Increment attempts

            Log::warning("Failed login attempt for email: " . $request->email . " from IP: " . $request->ip());

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // 5. Clear rate limit on successful login
        RateLimiter::clear($throttleKey);

        // 6. Revoke old tokens (optional but recommended)
        $user->tokens()->delete();

        // 7. Create new Sanctum token with expiration
        $token = $user->createToken('api-login', ['*'], now()->addMinutes($this->tokenExpiration))->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'token_expires_at' => now()->addMinutes($this->tokenExpiration)->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        // 1. Revoke the current user's token
        $request->user()->currentAccessToken()->delete();

        // 2. Optional: Revoke ALL user tokens (uncomment if needed)
        // $request->user()->tokens()->delete();

        // 3. Return a success response
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function checkToken(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'valid' => false,
            'message' => 'No authenticated user',
        ], 401);
    }

    $token = $user->currentAccessToken();

    if (!$token) {
        return response()->json([
            'valid' => false,
            'message' => 'No active token found',
        ], 401);
    }

    $expiresAt = $token->created_at->addMinutes($this->tokenExpiration);
    $remainingMinutes = now()->diffInMinutes($expiresAt, false); // Returns negative if expired

    if ($remainingMinutes <= 0) {
        $user->currentAccessToken()->delete(); // Auto-logout
        return response()->json([
            'valid' => false,
            'message' => 'Token expired',
            'remaining_minutes' => 0,
        ], 401);
    }

    return response()->json([
        'valid' => true,
        'expires_at' => $expiresAt->toDateTimeString(),
        'remaining hours' => $remainingMinutes / 60,
        'remaining_minutes' => $remainingMinutes,
        'remaining_seconds' => now()->diffInSeconds($expiresAt), // More precise
    ]);
}
}
