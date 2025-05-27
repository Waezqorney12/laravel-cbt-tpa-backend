<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MateriResource;
use App\Models\DetailMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Materi;
use App\Models\MateriImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{


    public function createMaterial(Request $request): JsonResponse
    {
        try {
            $requestData = Validator::make($request->all(), [
                'class_id' => 'required|integer|exists:kelas,id',
                'materi_title' => 'required|string',
                'materi_description' => 'required|string',
                'materi_kategori' => 'required|string',
                'materi_image_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($requestData->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'error' => $requestData->errors()
                ], 400);
            }

            $data = $requestData->validate();

            $existingData = Materi::where('materi_title', $data['materi_title'])->first();
            if ($existingData) {
                return response()->json([
                    'message' => 'Materi already exist',
                    'error' => 'Cannot create material that already exist'
                ], status: 400);
            }
            Materi::create([
                'class_id' => $data['class_id'],
                'materi_title' => $data['materi_title'],
                'materi_description' => $data['materi_description'],
                'materi_kategori' => $data['materi_kategori'],
            ]);


            $materi = Materi::where('materi_title', $data['materi_title'])->first();
            if ($request->hasFile('materi_image_path')) {
                $image = $request->file('materi_image_path');
                $imagePath = $image->store('materis_image', 's3');
                Storage::disk('s3')->setVisibility($imagePath, 'public');


                MateriImage::create([
                    'materi_id' => $materi->id,
                    'materi_image_path' => $imagePath,
                ]);
            }
            return response()->json([
                'message' => 'Operation success',
                'data' => 'Material has been created successfully'
            ], status: 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed operation to create materials',
                'error' => $th->getMessage()
            ], status: 500);
        }
    }
    public function joinMateri(Request $request): JsonResponse
    {
        try {
            $userId = auth()->user()->id;
            $requestData = Validator::make($request->all(), [
                'materi_id' => 'required|integer'
            ]);
            if ($requestData->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'error' => $requestData->errors()
                ], status: 422);
            }
            $data = $requestData->validate();
            $quiz = DetailMateri::create(
                [
                    'user_id' => $userId,
                    'materi_id' => $data['materi_id']
                ]
            );
            if ($quiz->isEmpty()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'error' => 'Empty data'
                ], status: 400);
            }
            return response()->json([
                'message' => 'Operation success',
                'data' => 'Successfully joined the material'
            ], status: 200);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => 'Server unavailable',
                    'error' => $th->getMessage()
                ]
            );
        }
    }
    public function getMateri(Request $request): JsonResponse
    {
        try {
            $user = auth()->check() ? auth()->user() : null;
            $userId = $user->id;
            $requestData = Validator::make($request->all(), [
                'class_id' => 'required|integer',
                'materi_title' => 'string|nullable',
            ]);
            if ($requestData->fails()) {
                return response()->json([
                    'message' => 'Operation failed',
                    'error' => $requestData->errors()
                ], status: 422);
            }
            $data = $requestData->validate();
            $title = $data['materi_title'] ?? null;
            $material = Materi::when($title, function ($query) use ($title) {
                if ($title) {
                    return $query->where('materi_title', $title);
                }
            })
                ->leftJoin('detail_materi', function ($join) use ($userId) {
                    $join->on('materi.id', '=', 'detail_materi.materi_id')
                        ->where('detail_materi.user_id', '=', $userId);
                })
                ->where('class_id', $data['class_id'])
                ->with(['image'])
                ->select('materi.*', DB::raw('IFNULL(detail_materi.status, NULL) as status'))
                ->get();


            return response()->json([
                'message' => 'Materi data retrieved successfully',
                'data' => MateriResource::collection($material)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to get materi',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(), [
                'materi_id' => 'required|integer|exists:materi,id',
                'status' => 'required|integer|max:100'
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validate->errors()
                ], 400);
            }
            $validateData = $validate->validate();

            $materi = DetailMateri::where('user_id', $request->user()->id)
                ->where('materi_id', $validateData['materi_id'])
                ->first();

            if (!$materi) {
                return response()->json([
                    'message' => '404 Not found',
                    'errors' => 'Materi not found'
                ], 404);
            }
            $materi->update(['status' => $validateData['status']]);

            return response()->json([
                'message' => 'Operation Success',
                'data' => 'Status updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to update status',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function show(Request $request)
    {
        //
    }
}
