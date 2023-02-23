<?php

use App\Http\Controllers\TestController;
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

Route::prefix('test')
->controller(TestController::class)
->group(function () {
    Route::get('abc', 'abc');
});

Route::post('upload', [TestController::class, 'upload']);
Route::get('calendar', [TestController::class, 'calendar']);
Route::post('add', [TestController::class, 'add']);
