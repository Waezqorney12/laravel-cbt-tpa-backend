<?php

use App\Events\UserUpdated;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UjianController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\UserMateriController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Verified;
use App\Models\User;



Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes

    // GET
    Route::get('/get-user', [AuthController::class, 'getUser']);
    // POST
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-profile', [AuthController::class, 'changeProfile']);

    // Ujian Routes

    // GET
    Route::get('/get-hasil-nilai', [UjianController::class, 'hitungNilaiUjianByKategori']);
    Route::get('/nilai', [UjianController::class, 'getAllNilai']);
    Route::get('/get-soal-ujian', [UjianController::class, 'getListSoalByKategori']);
    // POST
    Route::post('/create-ujian', [UjianController::class, 'createUjian']);
    Route::post('/exit', [UjianController::class, 'exitUser']);
    Route::post('/answers', [UjianController::class, 'jawabSoal']);

    // Materi Routes

    // GET
    Route::get('/get-materi/{materi_kategori}', [MateriController::class, 'getMateri']);

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json('Verification link sent!');
    })->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');



    // PUT
    Route::put('update-value', [UserMateriController::class, 'updateValueMaterial']);


});
// POST
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('assign-materi', [UserMateriController::class, 'store']);

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::find($id);

    if (!$user || !hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        // The id and hash didn't match a valid user, or the email has changed.
        return response()->json(['message' => 'Invalid verification link'], 400);
    }

    if ($user->hasVerifiedEmail()) {
        // The email was already verified.
        return response()->json(['message' => 'Email already verified'], 400);
    }

    $user->markEmailAsVerified();
    event(new Verified($user));

    return response()->json('Email verified!');
})->name('verification.verify');






