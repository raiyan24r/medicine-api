<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMedicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
//    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('user-medication')->group(function () {
        Route::controller(UserMedicationController::class)->group(function () {
            Route::post('/add-drug', 'addDrug');
            Route::delete('/delete-drug/{rxcui}', 'deleteDrug');
//        Route::get('/drugs', 'getDrugs');
        });
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::get('/search-drug', [DrugController::class, 'search'])->middleware('throttle:20,1');




