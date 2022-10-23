<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Security\AuthController;
use \App\Http\Controllers\FileController;;
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

// Route::middleware('auth:passport')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1.0'],function() {
    /**
     *  Routes of autenticate
     */
    Route::post('/register', [AuthController::class, 'registerUser']);
    Route::post('/login', [AuthController::class, 'loginUser']);
    
    /**
     *  Routes of files
     */
    Route::get('/files', [FileController::class, 'index'])->name('list_files')->middleware('auth:api');
    Route::get('/file/{id}', [FileController::class, 'show'])->name('details_file')->middleware('auth:api');
    Route::post('/file', [FileController::class, 'store'])->name('store_file')->middleware('auth:api');
    Route::put('/file/{id}', [FileController::class, 'update'])->name('update_file')->middleware('auth:api');
    Route::delete('/file/{id}', [FileController::class, 'destroy'])->name('delete_file')->middleware('auth:api');
    
});