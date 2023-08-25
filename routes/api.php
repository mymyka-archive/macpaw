<?php

use App\Http\Controllers\Api\V1\UserController;
use App\Models\Collection;
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

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('collections', CollectionController::class);
    Route::apiResource('contributors', ContributorController::class);

    Route::controller(UserController::class)->group(function () {
        Route::post('users/login', 'login');
        Route::post('users/register', 'register');
        Route::post('users/logout', 'logout');
        Route::post('users/refresh', 'refresh');
    });
});