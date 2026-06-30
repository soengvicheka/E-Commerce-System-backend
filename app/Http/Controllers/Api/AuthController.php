<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

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
            // Prevent reuse by requiring state is NOT sent (we don't use state in this simple flow)
            // Socialite exchanges the authorization code for user info using client_secret on the backend.
            $socialiteUser = Socialite::driver('google')->stateless()->userFromCode($request->code);

            $user = \App\Models\User::where('email', $socialiteUser->getEmail())->first();

            if (! $user) {
                $user = \App\Models\User::create([
                    'name'     => $socialiteUser->getName() ?? 'Google User',
                    'email'    => $socialiteUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'avatar'   => $socialiteUser->getAvatar(),
                ]);
            } else {
                if ($socialiteUser->getAvatar()) {
                    $user->update(['avatar' => $socialiteUser->getAvatar()]);
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
