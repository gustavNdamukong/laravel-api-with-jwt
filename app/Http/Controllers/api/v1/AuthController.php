<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Return success response
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to authenticate
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $accessToken = $user->createToken('access-token')->plainTextToken;
        $refreshToken = Str::random(60);

        $user->update(['refresh_token' => hash('sha256', $refreshToken)]);

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
        ]);
    }


    public function refreshToken(Request $request)
    {
        $refreshToken = $request->refresh_token;

        $user = User::where('refresh_token', hash('sha256', $refreshToken))->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        // Create a new access token
        $newAccessToken = $user->createToken('access-token')->plainTextToken;

        return response()->json([
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer',
        ]);
    }
}
