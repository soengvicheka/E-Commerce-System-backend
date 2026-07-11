<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    // -----------------------------------------------------------------
    // Google Social Login – frontend redirect flow
    // -----------------------------------------------------------------

    public function googleCallback(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        try {
            $redirectUri = config('services.google.redirect');

            $tokenResponse = Http::timeout(10)
                ->withOptions(['verify' => false])
                ->post('https://oauth2.googleapis.com/token', [
                    'code' => $request->code,
                    'client_id' => config('services.google.client_id'),
                    'client_secret' => config('services.google.client_secret'),
                    'redirect_uri' => $redirectUri,
                    'grant_type' => 'authorization_code',
                ]);

            if (! $tokenResponse->successful()) {
                return response()->json([
                    'message' => 'Google token exchange failed',
                    'google_error' => $tokenResponse->json(),
                ], 401);
            }

            $accessToken = $tokenResponse->json('access_token');

            $socialiteUser = Http::timeout(10)
                ->withOptions(['verify' => false])
                ->withToken($accessToken)
                ->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if (! $socialiteUser->successful()) {
                return response()->json([
                    'message' => 'Google user info fetch failed',
                    'google_error' => $socialiteUser->json(),
                ], 401);
            }

            $payload = $socialiteUser->json();
            $user = \App\Models\User::where('email', $payload['email'] ?? '')->first();

            if (! $user) {
                $user = \App\Models\User::create([
                    'name'     => $payload['name'] ?? 'Google User',
                    'email'    => $payload['email'],
                    'password' => Hash::make(Str::random(24)),
                    'avatar'   => $payload['picture'] ?? null,
                ]);
            } else {
                if (!empty($payload['picture'])) {
                    $user->update(['avatar' => $payload['picture']]);
                }
                if (empty($user->email_verified_at)) {
                    $user->update(['email_verified_at' => now()]);
                }
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'user'  => $user,
                'token' => $token,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Google authentication failed: ' . $e->getMessage()], 401);
        }
    }
}
