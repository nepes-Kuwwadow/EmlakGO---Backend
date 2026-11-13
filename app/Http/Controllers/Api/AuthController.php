<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Täze ulanyjy hasaba almak
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone'    => $validated['phone'] ?? null,
            'role'     => 'user',
        ]);

        $apiToken = $user->createApiToken('register');

        return response()->json([
            'message' => 'Hasap üstünlikli döredildi.',
            'token'   => $apiToken->token,
            'user'    => $this->userResource($user),
        ], 201);
    }

    /**
     * Ulanyjy giriş
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email ýa-da parol nädogry.'],
            ]);
        }

        $apiToken = $user->createApiToken('login');

        return response()->json([
            'message' => 'Üstünlikli girildi.',
            'token'   => $apiToken->token,
            'user'    => $this->userResource($user),
        ]);
    }

    /**
     * Ulanyjy çykyş
     */
    public function logout(Request $request)
    {
        $bearerToken = $request->bearerToken();

        if ($bearerToken) {
            $request->user()->apiTokens()
                ->where('token', $bearerToken)
                ->delete();
        }

        return response()->json([
            'message' => 'Üstünlikli çykyldy.',
        ]);
    }

    /**
     * Häzirki ulanyjy maglumatlaryny almak
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $this->userResource($request->user()),
        ]);
    }

    /**
     * Ulanyjy maglumatlaryny formatlamak
     */
    private function userResource(User $user): array
    {
        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role'  => $user->role,
        ];
    }
}
