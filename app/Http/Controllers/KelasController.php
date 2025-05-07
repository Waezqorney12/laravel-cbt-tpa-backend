<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use App\Models\kelas_detail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\FlareClient\Solutions\ReportSolution;

class KelasController extends Controller
{


    public function getKelas(): JsonResponse
    {
        try {
            $userId = auth()->user()->id;
            $getKelas = kelas_detail::with(['class', 'class.user'])->where('user_id', $userId)->get();


            $transformedData = $getKelas->map(function ($classes) {
                $members = kelas_detail::with([
                    'user',
                    'user.images'
                ])
                    ->where('class_id', $classes->class_id)
                    ->get()
                    ->map(function ($users) {
                        $user = $users->user;
                        if ($user->roles === "USER") {
                            return [
                                'id' => $user->id,
                                'first_name' => $user->dataPribadi->first_name,
                                'last_name' => $user->dataPribadi->last_name,
                                'images' => $user->images?->user_image_path
                            ];
                        }
                        return null;
                    })->filter()->values();
                $class = $classes->class;
                return [
                    'class_detail_id' => $classes->id,
                    'class_id' => $class->id,
                    'class_name' => $class->class_name,
                    'class_description' => $class->class_description,
                    'class_color' => $class->class_color,
                    'class_pin' => $class->class_pin,
                    'class_author' => $class->user->dataPribadi->full_name,
                    'members' => $members,
                    'created_at' => $class->created_at,
                    'updated_at' => $class->updated_at
                ];
            });

            return response()->json([
                'message' => $userId,
                'data' => $transformedData
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'error' => $th->getMessage()
            ], status: 500);
        }
    }

    public function joinKelas(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $validateData = Validator::make($request->all(), [
                'class_pin' => 'required|string|exists:kelas,class_pin'
            ]);
            if ($validateData->fails()) {
                return response()->json([
                    'message' => 'Class not found',
                    'error' => $validateData->errors()
                ], status: 400);
            }
            $data = $validateData->validate();
            $kelas = kelas::where('class_pin', $data['class_pin'])->first();
            if (!$kelas) {
                return response()->json([
                    'message' => 'Error 404',
                    'error' => 'Class not found'
                ], status: 404);
            }
            $existingClass = kelas_detail::where('class_id', $kelas->id)->where('user_id', $userId)->first();
            if ($existingClass) {
                return response()->json([
                    'message' => 'Unknown error',
                    'error' => 'Already joined the class'
                ]);
            }
            kelas_detail::create([
                'class_id' => $kelas->id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return response()->json([
                'message' => 'Operation Success',
                'data' => 'User joined class successfully'

            ], status: 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'error' => $th->getMessage()
            ], status: 500);
        }
    }
    function generateRandomColor()
    {

        return '#' . str_pad(dechex(mt_rand(0, 0x7F7F7F)), 6, '0', STR_PAD_LEFT);
    }
    public function createKelas(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if ($user->roles !== 'STAFF') {
                return response()->json([
                    'message' => 'Failed to add class',
                    'error' => 'Unauthorized'
                ], status: 403);
            }
            $responseData = Validator::make($request->all(), [
                'class_name' => 'required|string',
                'class_description' => 'nullable|string',
            ]);
            if ($responseData->fails()) {
                return response()->json([
                    'message' => 'Data not found',
                    'error' => $responseData->errors()
                ], status: 400);
            }
            $data = $responseData->validate();
            $attempt = 0;
            $maxAttempt = 100;
            $newPin = null;

            do {
                $newPin = str_pad(random_int(00000, 99999), 5, '0', STR_PAD_RIGHT);
                $attempt++;

                if ($attempt >= $maxAttempt) {
                    return response()->json([
                        'message' => 'Failed to generate a unique class pin',
                        'error' => 'Please try again later'
                    ], status: 500);
                }
            } while (kelas::where('class_pin', $newPin)->exists());
            $color = $this->generateRandomColor();
            $kelas = Kelas::create([
                'class_name' => $data['class_name'],
                'class_description' => $data['class_description'],
                'class_color' => $color,
                'class_pin' => $newPin,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            if ($user->roles === 'STAFF') {
                kelas_detail::create([
                    'class_id' => $kelas->id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'message' => 'class data successfully added',
                'data' => $kelas
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'error' => $th->getMessage()
            ]);
        }
    }
}
