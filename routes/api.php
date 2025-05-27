<?php

use App\Events\UserUpdated;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReportController;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Verified;
use App\Models\User;



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/send-report', [ReportController::class, 'SendReport']);
    Route::get('/view-report-history', [ReportController::class, 'ViewHistoryReport']);
    // Auth Routes

    // GET
    Route::get('/get-user', [AuthController::class, 'getUser']);
    // POST
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-profile', [AuthController::class, 'changeProfile']);

    // Materi Routes

    // POST
    Route::post('/join-materi', [MateriController::class, 'joinMateri']);
    Route::post('/generate-materi-user', [MateriController::class, 'generateMateriUser']);
    Route::post('/create-material', [MateriController::class, 'createMaterial']);
    // PUT
    Route::put('/update-status', [MateriController::class, 'updateStatus']);

    // GET
    Route::get('/get-materi/{materi_kategori?}', [MateriController::class, 'getMateri']);


    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json('Verification link sent!');
    })->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');


    // QUIZ Routes

    Route::get('/get-quiz-thumbnail', [QuizController::class, 'getQuizThumbnail']);

    // POST
    Route::post('/create-question', [QuizController::class, 'createQuestion']);

    Route::put('/edit-quizzes', [QuizController::class, 'editQuizzes']);
    Route::put('/update-answer', [QuizController::class, 'answerQuizzes']);
    Route::put('/exit-quiz', [QuizController::class, 'exitQuizzes']);
    Route::put('/show-score-quiz', [QuizController::class, 'calculateQuizzes']);


    // KELAS Routes
    Route::post('/create-class', [KelasController::class, 'createKelas']);
    Route::post('/join-kelas', [KelasController::class, 'joinKelas']);

    Route::get('/get-class', [KelasController::class, 'getKelas']);

    Route::post('/join-quizzes', [QuizController::class, 'joinQuizzes']);
    Route::post('/generate-quizzes', [QuizController::class, 'generateQuizzes']);

    Route::post('/upload-image', [AuthController::class, 'uploadImage']);
});
// Quiz Routes

// GET
Route::get('/get-question', [QuizController::class, 'getQuestion']);

// POST
// Auth Routes
Route::post('/send-otp', [AuthController::class, 'sendResetOTP']);
Route::post('/reset-password', [AuthController::class, 'sendResetPassword']);
Route::post('/sign-in', [AuthController::class, 'signIn']);
Route::post('/sign-up', [AuthController::class, 'register']);

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






