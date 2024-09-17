<?php

namespace App\Http\Controllers\Api;

use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function userUpdate(Request $request)
    {
        try {
            $user = $request->user();
            $user->update($request->all());
            broadcast(new UserUpdated($user))->toOthers();

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to update user',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|max:55',
                'email' => 'email|required|unique:users',
                'password' => 'required'
            ]);

            $user = User::create([
                'name' => $validateData['name'],
                'email' => $validateData['email'],
                'password' => Hash::make($validateData['password']),
                'roles' => 'USER'
            ]);

            event(new Registered($user));
            $user->sendEmailVerificationNotification();
            return response()->json([
                'message' => 'Registration successful, check your email for account activation',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed. ' . $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $user = User::where('email', $loginData['email'])->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 401);

        }
        if (!$user->hasVerifiedEmail()) {
            Log::info('Email verification status at login: ' . $user->hasVerifiedEmail());
            return response()->json([
                'message' => 'Email not verified',
                'email_verified' => $user->hasVerifiedEmail(),
            ], 403);
        }

        if (!Hash::check($loginData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'message' => UserResource::make($user)
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Log out Success'
        ]);
    }

    public function changeProfile(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'string|max:255',
                'email' => 'string|email|max:255|unique:users,email,' . $request->user()->id,
                'phone' => 'nullable|string|max:20',
            ]);

            $user = $request->user();

            if ($user->roles == 'USER') {
                $user->update($validatedData);
                return response()->json([
                    'message' => 'Profile updated',
                    'data' => $user
                ]);
            } else {
                return response()->json([
                    'message' => 'Action is not allowed'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function update(Request $request, string $id)
    {
    }
    public function destroy(string $id)
    {
    }
}
