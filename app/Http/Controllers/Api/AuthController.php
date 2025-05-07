<?php

namespace App\Http\Controllers\Api;

use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\DetailMateri;
use App\Models\PersonalInformation;
use App\Models\User;
use App\Models\UserImages;
use App\Models\UserOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function generateOtpNumber($length = 4)
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= mt_rand(0, 9);
        }
        return $otp;
    }

    private function generateUniqueUsername()
    {
        $maxAttempts = 5;
        $attempt = 0;
        $maxLength = 10;

        do {
            $randomString = Str::random($maxLength); // Generate a random 3-digit number
            $username = $randomString;
            $attempt++;
        } while (User::where('username', $username)->exists() && $attempt < $maxAttempts);

        if ($attempt == $maxAttempts) {
            // Fallback to a hash-based username if too many attempts fail
            $username = substr(sha1(uniqid('', true)), 0, $maxLength);
        }

        return $username;
    }

    public function sendResetOTP(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validate->errors()
                ], 400);
            }
            $validateData = $validate->validate();
            $user = User::where('email', $validateData['email'])->first();
            if (!$user) {
                return response()->json([
                    'message' => '404 not found',
                    'errors' => 'User not found'
                ], 404);
            }
            $otp = $this->generateOtpNumber();
            UserOtp::create([
                'user_id' => $user->id,
                'otp' => $otp
            ]);
            Mail::raw('Your new OTP number is ' . $otp, function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('OTP Code');
            });
            return response()->json([
                'message' => 'OTP sent successfully',
                'data' => 'Please check your email'
            ], 200);


        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to send OTP',
                'error' => $th->getMessage()
            ], 500);
        }
    }



    public function sendResetPassword(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(), [
                'otp' => 'required|string|max:4',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validate->errors()
                ], 400);
            }
            $validateData = $validate->validate();
            $userOtp = UserOtp::with('user')->where(
                'otp',
                $validateData['otp']
            )->first();
            if (!$userOtp) {
                return response()->json([
                    'message' => '404 not found',
                    'errors' => 'Invalid not found'
                ], 404);
            }
            $newPassword = Str::random(8);
            $hashedPassword = Hash::make($newPassword);

            $user = $userOtp->user;
            $user->update(['password' => $hashedPassword]);

            $userOtp->update(['otp' => null]);

            Mail::raw('Your new password is ' . $newPassword, function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('New Password');
            });

            return response()->json([
                'message' => 'Success to send reset password',
                'data' => 'Please check your email'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to send reset password',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function getUser(Request $request): JsonResponse
    {
        try {
            $user = User::with(['images', 'dataPribadi'])
                ->where('id', $request->user()->id)
                ->first();
            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }

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
            $validatorData = Validator::make($request->all(), [

                'matrix_id' => 'required|string|exists:personal_information,matrix_id',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8'
            ]);
            if ($validatorData->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validatorData->errors()
                ], 400);
            }
            $data = $validatorData->validate();

            $personalInformation = PersonalInformation::where('matrix_id', $data['matrix_id'])->first();
            if (!$personalInformation) {
                return response()->json([
                    'message' => 'Matrix ID not found in personal information'
                ], 404);
            }
            $personalId = $personalInformation->id;
            $username = $this->generateUniqueUsername();
            $user = User::create([
                'personal_id' => $personalId,
                'email' => $data['email'],
                'username' => $username,
                'password' => Hash::make($data['password']),
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
                ], status: 400);
            }

            $validatedData = $validate->validate();
            $user = $request->user();


            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('profile_images', 'public'); // Store the image in the 'public/profile_images' directory

                $userImage = UserImages::updateOrCreate(
                    ['user_id' => $user->id],
                    ['user_image_path' => $imagePath]
                );
                $validatedData['image'] = $userImage->user_image_path;
            }

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
