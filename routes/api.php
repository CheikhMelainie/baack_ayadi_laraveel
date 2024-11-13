<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransactionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test-api', function () {
    return response()->json(['message' => 'Hello from Laravel API']);
});

Route::get('/get-session-id', [TransactionController::class, 'getSessionId']);
Route::post('/transaction/create', [TransactionController::class, 'createTransaction']);

