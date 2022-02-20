<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * Register a new user using name, email, password and password_confirmation
     * where all are required fields.
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

        try {
            // Creating new user with the provided data.
            $user = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
            ]);

            // Creating a session for the user.
            $token = $this->createSession($user);

            // Return JSON response on success.
            return response([ 'user' => $user, 'token' => $token ], 201);
        } catch (Exception $e) {
            // Return exception error.
            return response($e);
        }
    }

    /**
     * Login the user using email and password, where all are required fields.
     *
     * @param Request
     * @return Response
     **/
    public function login(Request $request): Response
    {
        // validating request body.
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        try {
            // Getting the username and password from the request body and storing it in $credentials variable.
            $credentials = request(['email', 'password']);

            // Trying to login the user using the provided username and password.
            if (!auth()->attempt($credentials)) {
                return response(["message" => "Credentials does not match"], 400);
            }

            // If the given credential is correct, we are getting the user data from database.
            $user = User::where('email', $request->email)->first();

            // Creating a session for the user.
            $token = $this->createSession($user);

            // Return JSON response on success.
            return response([ 'user' => $user, 'token' => $token ], 200);
        } catch (Exception $e) {
            // Return exception error.
            return response($e);
        }
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
