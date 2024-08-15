<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Materi;

class MateriController extends Controller
{

    // This is function that i created for get all materi
    public function show(Request $request)
    {
        $user = $request->user();
        $materi = $user->materis()->get();

        return response()->json($materi);
    }
}
