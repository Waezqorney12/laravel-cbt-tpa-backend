<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $materi = \App\Models\Materi::when($request->id, function ($query, $id) {
            return $query->where('id', $id);
        })->get();
        return response()->json($materi);
    }
}
