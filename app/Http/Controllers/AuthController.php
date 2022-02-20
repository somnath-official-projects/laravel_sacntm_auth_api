<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * Register a new user using name, email, password and password_confirmation
     * where all are required field.
     *
     * @param Request
     * @return Response
     **/
    public function register(Request $request): Response
    {
        // Validating request body.
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|confirmed'
        ]);

        // Creating new user with the provided data.
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // Create a session for the user.
        $token = $this->createSession($user);

        // Return JSON response on success.
        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);

        if (!auth()->attempt($credentials)) {
            return response(["message" => "Credentials does not match"], 400);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(["message" => "Successfully logout"], 200);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response(["message" => "Successfully logout from all sessions."], 200);
    }

    /**
     * Create a session for user and return the access token
     *
     * @param User
     * @return string
     **/
    public function createSession(User $user): string
    {
        return $user->createToken('authToken')->plainTextToken;
    }
}
