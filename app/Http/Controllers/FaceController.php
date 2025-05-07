<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaceController extends Controller
{
    public function registerFace(Request $request): JsonResponse
    {
        try {
            return response()->json(['message' => 'Face registered and model updated']);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server error',
                'error' => $th->getMessage()
            ], status: 500);
        }
    }
}
