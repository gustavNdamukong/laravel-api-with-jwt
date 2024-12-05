<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\CustomPersonalAccessToken;


/**
 * @OA\Info(
 *  title="Laravel 11 REST API with Sanctum for token-based authentication",
 *  version="1.0.0"
 * )
 *
 */
class AuthController extends Controller
{
    /**
     *  @param \Illuminate\Http\Request $request
     *  @return mixed|\Illuminate\Http\JsonResponse
     *
     *  @OA\Post(
     *      path="/laravel-api-with-jwt/public/api/v1/signup",
     *      operationId="register",
     *      tags={"Register"},
     *      summary="Register a new user",
     *      description="This is the endpoint to create new users",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *                  type="object",
     *                  required={"name","email", "password", "password_confirmation"},
     *                  @OA\Property(property="name", type="text", description="The name of the user", example="John Doe"),
     *                  @OA\Property(property="email", type="text", description="The email of the user", example="johnd@gmail.com"),
     *                  @OA\Property(property="password", type="string", description="Enter the password", example="12345678"),
     *                  @OA\Property(property="password_confirmation", type="string", description="Confirm the password", example="12345678")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              type="object",
     *               @OA\Property(property="message", type="string", example="User registered successfully"),
     *               @OA\Property(property="user", type="object")
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object")
     *          )
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *  )
     */
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


    /**
     *  @param \Illuminate\Http\Request $request
     *  @return mixed|\Illuminate\Http\JsonResponse
     *
     *  @OA\Post(
     *      path="/laravel-api-with-jwt/public/api/v1/login",
     *      operationId="login",
     *      tags={"Login"},
     *      summary="Login a user",
     *      description="This is the endpoint to login users",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="text", description="The email of the user", example="johnd@gmail.com"),
     *              @OA\Property(property="password", type="string", description="Enter the password", example="12345678")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login successful",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Login successful"),
     *              @OA\Property(property="user", type="object")
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object")
     *          )
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *  )
     */
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

        // Create access token
        $token = $user->createToken('access-token');
        $accessToken = $token->plainTextToken;

        // Generate and hash the refresh token
        $refreshToken = Str::random(60);
        $hashedRefreshToken = hash('sha256', $refreshToken);

        // Save refresh token and expiration in personal_access_tokens table
        CustomPersonalAccessToken::where('id', $token->accessToken->id)->update([
            'refresh_token' => $hashedRefreshToken,
            'expires_at' => now()->addDays(30),
        ]);

        // we return the raw (unhashed) refresh token
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
        ]);
    }


    /**
     *  @param \Illuminate\Http\Request $request
     *  @return mixed|\Illuminate\Http\JsonResponse
     *
     *  @OA\Post(
     *      path="/laravel-api-with-jwt/public/api/v1/refresh_token",
     *      operationId="refresh_token",
     *      tags={"RefreshToken"},
     *      summary="Refresh a user access token",
     *      description="This is the endpoint to refresh a user access token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"refresh_token"},
     *              @OA\Property(property="refresh_token", type="string", description="Your refresh token"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Token refresh successful",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Token refresh successful"),
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given refresh token was invalid."),
     *              @OA\Property(property="errors", type="object")
     *          )
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *  )
     */
    public function refreshToken(Request $request)
    {
        $refreshToken = $request->refresh_token;

        // Hash the received token
        $hashedRefreshToken = hash('sha256', $refreshToken);

        // We hash the received token to match what is stored in the database
        $tokenRecord = DB::table('personal_access_tokens')
        ->where('refresh_token', $hashedRefreshToken)
        ->where('expires_at', '>', now()) // Optional: Check if token is expired
        ->first();

        if (!$tokenRecord) {
            return response()->json(['message' => 'Invalid or expired refresh token'], 401);
        }

        $user = User::find($tokenRecord->tokenable_id);

        // Create a new access token
        $newAccessToken = $user->createToken('access-token')->plainTextToken;

        return response()->json([
            "message" => "Token refresh successful",
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer',
        ]);
    }


    public function logout(Request $request)
    {
        // get all tokens of logged in user and delete them
        // it will delete all tokens that were ever given to this user
        $request->user()->tokens()->delete();

        /*
        // OR same thing as:

        auth()->user()->tokens()->delete();
        */
        return response()->json([
            "message" => "Logout successful"
        ]);
    }

    // token id is the id
    public function deleteSingleToken($tokenId)
    {
        $token = request()->user()->tokens->where("id", $tokenId)->first();
        $allUserTokens = request()->user()->tokens;

        if ($token) {
            $token->delete();

            return response()->json([
                "message" => "Token deleted",
                "deleted_id" => $tokenId,
                "userTokens" => $allUserTokens
            ]);
        }
        return response()->json([
            "message" => "Token not found",
            "token_id" => $tokenId,
            "userTokens" => $allUserTokens
        ]);
    }
}
