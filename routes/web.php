<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\KelasWebController;
use App\Http\Controllers\MateriWebController;
use App\Http\Controllers\QuizWebController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('pages.auth.login');
})->name('login.view'); // Renamed to avoid conflict

Route::post('/login', [UserController::class, 'login'])->name('login.post'); // No change

Route::middleware(['auth'])->group(function () {
    Route::get('home', function () {
        return view('pages.dashboard');
    })->name('home');
    Route::resource('users', UserController::class);
    Route::resource('soal', SoalController::class);
    Route::resource('quiz', QuizWebController::class);
    Route::resource('materi', MateriWebController::class);
    Route::resource('kelas', KelasWebController::class);
});