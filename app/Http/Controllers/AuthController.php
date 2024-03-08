<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Validated
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'lastname' => 'required|string',
                'email' => 'required|email|string|unique:users,email',
                'username' => 'required|string|unique:users,username',
                'password' => 'required',
            ]
        );

        // Message if validation fails
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        // Create User
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Return response
        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("TOKEN")->plainTextToken,
            'user' => $request->only('name', 'lastname', 'email', 'username'),
        ], 200);
    }

    public function login(Request $request)
    {
        //Validated
        $validateUser = Validator::make(
            $request->all(),
            [
                'username' => [
                    'required',
                    'exists:users,username',
                ],
                'password' => [
                    'required'
                ],
            ]
        );

        $validateUser->setCustomMessages([
            'username.exists' => 'El user no existe en la base de datos',
        ]);

        // Message if validation fails

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        };

        // Check if user exists

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'The Provided credentials are not correct',
            ], 401);
        };

        // Get user data

        $user = User::where('username', $request->username)->first();

        return response()->json([
            'status' => true,
            'message' => 'Loggin Successfully',
            'token' => $user->createToken("TOKEN")->plainTextToken,
            'user' => $user->only('name', 'lastname', 'email', 'username'),
        ], 200);
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }
}
