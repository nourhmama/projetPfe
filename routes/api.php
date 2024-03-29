<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ContractController;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum','verified')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/admin/dashboard', function () {
    //
})->middleware(['auth', 'role:admin']);

    // Authentification User
    Route::post('login', [AuthController::class, 'login'])->name('login');


    Route::post('register', [AuthController::class, 'register']);
    Route::delete('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Vérification email
    Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

    // Oublier password
    Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [NewPasswordController::class, 'reset']);

    // Routes pour les utilisateurs
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    //route pour l'admin
    Route::post('admin/login', [AuthController::class, 'adminLogin']);
    Route::delete('admin/logout', [AuthController::class, 'adminLogout'])->middleware('auth:sanctum');
 // Routes pour les contracts
Route::get('contracts', [ContractController::class, 'index']);
Route::get('contracts/{contract}', [ContractController::class, 'show']);
Route::post('contracts', [ContractController::class, 'store']);
Route::put('contracts/{contract}', [ContractController::class, 'update']);
Route::delete('contracts/{contract}', [ContractController::class, 'destroy']);
