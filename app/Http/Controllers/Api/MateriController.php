<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MateriResource;
use App\Models\DetailMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Materi;
use Illuminate\Http\JsonResponse;

class MateriController extends Controller
{

    public function getMateri(Request $request, $materi_kategori): JsonResponse
    {
        try {
            $materi = DetailMateri::with([
                'materi' => function ($query) use ($materi_kategori) {
                    $query->where('materi_kategori', $materi_kategori);
                }
            ])->where('user_id', $request->user()->id)->get();

            $materi = $materi->filter(function ($detailMateri) {
                return $detailMateri->materi !== null;
            });

            return response()->json([
                'message' => 'Materi data retrieved successfully',
                'data' => MateriResource::collection($materi)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to get materi',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function show(Request $request)
    {
        //
    }
}
