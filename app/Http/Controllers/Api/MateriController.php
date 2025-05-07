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
                $imagePath = $image->store('materis_image', 'public');


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
    public function generateMateriUser(): JsonResponse
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'message' => 'Operation Failed',
                    'data' => 'User not authenticated'
                ], 401);
            }
            $materi = Materi::inRandomOrder()->limit(100)->get();

            if ($materi->isEmpty()) {
                return response()->json([
                    'message' => 'Operation Failed',
                    'data' => 'No materi generated'
                ], 400);
            }

            $existingMateriIds = DetailMateri::where('user_id', $user->id)
                ->pluck('materi_id')
                ->toArray();

            $detailMateri = [];
            foreach ($materi as $m) {

                if (!in_array($m->id, $existingMateriIds)) {
                    $detailMateri[] = [
                        'user_id' => $user->id,
                        'materi_id' => $m->id,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            if (count($detailMateri) === 0) {
                return response()->json([
                    'message' => 'All materi already assigned',
                    'data' => 'No new materi to assign'
                ], 200);
            }



            DetailMateri::insert($detailMateri);

            return response()->json([
                'message' => 'Operation successfully',
                'data' => 'Success to generate materi for user'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to generate materi',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function generateMateri(): JsonResponse
    {
        try {
            $fake = Faker::create();
            $materiList = [];

            for ($i = 0; $i < 100; $i++) {
                $materiList[] = [
                    'materi_title' => $fake->sentence(6),
                    'materi_description' => $fake->paragraph(3),
                    'materi_kategori' => $fake->randomElement(['Logika', 'Verbal', 'Numeric']),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            if (count($materiList) === 0) {
                return response()->json([
                    'message' => 'Operation Failed',
                    'data' => 'No materi generated'
                ], 400);
            }
            Materi::insert($materiList);

            $materi = Materi::get();

            $materiImageList = [];
            foreach ($materi as $m) {
                $materiImageList[] = [
                    'materi_id' => $m->id,
                    'materi_image_path' => "https://picsum.photos/500/300",
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            /*
            Truncate is similiar to insert it will delete all data and add newest
            data in the table with batch mode but faster, so it won't affect
            the performamce
            */
            MateriImage::truncate();
            MateriImage::insert($materiImageList);
            return response()->json([
                'message' => 'Operation Success',
                'data' => 'Materi generated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to generate materi',
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
