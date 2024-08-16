<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMateriController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input, including user_id since the admin provides it
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'materi_id' => 'required|exists:materis,id',
        ]);

        // Retrieve the user by user_id
        $user = User::findOrFail($validatedData['user_id']);

        // Check if the materi_id already exists for the user
        $existingMateri = $user->materis()->where('materi_id', $validatedData['materi_id'])->exists();

        if ($existingMateri) {
            return response()->json(['message' => 'This materi is already assigned to the user'], 400);
        }

        // Create the new entry in the pivot table with default value 0
        $user->materis()->attach($validatedData['materi_id'], ['value' => 0]);

        return response()->json(['message' => 'Materi successfully assigned to the user'], 201);
    }

    public function updateValueMaterial(Request $request)
    {
        $validatedData = $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'value' => 'required|integer|min:0|max:100',
        ]);

        $user = $request->user();

        $existingMateri = $user->materis()->where('materi_id', $validatedData['materi_id'])->exists();
        if (!$existingMateri) {
            return response()->json(['message' => 'Materi tidak ditemukan untuk user ini'], 404);
        }

        // Mengupdate nilai value pada tabel pivot
        $user->materis()->updateExistingPivot($validatedData['materi_id'], ['value' => $validatedData['value']]);

        return response()->json(['message' => 'Materi value successfully updated'], 200);
    }
}
