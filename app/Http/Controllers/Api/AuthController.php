<?php

namespace App\Http\Controllers\Api;

use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function getUser(Request $request): JsonResponse
    {
        try {
            $user = User::with('images')->where('id', $request->user()->id)->first();
            return response()->json([
                'message' => 'User data retrieved successfully',
                'data' => new UserResource($user)
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to get user',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function update(Request $request)
    {
        try {
            $user = $request->user();
            $user->update($request->all());
            broadcast(event(new UserUpdated($user)))->toOthers();

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

    public function register(Request $request): JsonResponse
    {
        try {
            $validateData = Validator::make($request->all(), [
                'username' => 'required|string|max:10|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8'
            ]);
            if ($validateData->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validateData->errors()
                ], 400);
            }

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles' => 'USER'
            ]);

            event(new Registered($user));
            $user->sendEmailVerificationNotification();
            return response()->json([
                'message' => 'Registration successful',
                'data' => 'check your email for account activation'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'Invalid data',
                'errors' => $validate->errors()
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Invalid data',
                'errors' => 'User not found'
            ], 401);

        }
        if (!$user->hasVerifiedEmail()) {
            Log::info('Email verification status at login: ' . $user->hasVerifiedEmail());
            return response()->json([
                'message' => 'Email not verified',
                'email_verified' => $user->hasVerifiedEmail(),
            ], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'errors' => 'Password not match'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login success',
            'data' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Log out Success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to logout',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function changeProfile(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'first_name' => 'string|max:255',
                'last_name' => 'string|max:255',
                'username' => 'string|max:255|unique:users,username,' . $request->user()->id,
                'phone_number' => 'string|nullable|unique:users,phone_number,' . $request->user()->id,
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validate->errors()
                ], 400);
            }

            $validatedData = $validate->validate();

            $user = $request->user();
            $user->update($validatedData);

            $updatedUser = User::with('images')->where('id', $user->id)->first();

            return response()->json([
                'message' => 'Profile updated successfully',
                'data' => new UserResource($updatedUser),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function destroy(string $id)
    {
    }
}
