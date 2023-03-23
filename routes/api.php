<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RequestApproveController;
use App\Http\Controllers\RequestController;
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

Route::get('calendar', [EventController::class, 'calendar']);
Route::post('save-event', [EventController::class, 'saveEvent']);
Route::get('approver', [RequestApproveController::class, 'listApprovers']);
Route::get('attendances/{date}', [CalendarController::class, 'attendances']);
Route::get('duration', [CalendarController::class, 'duration']);
Route::post('create-request', [RequestController::class, 'createRequest']);
Route::get('get-list-calendar-user', [CalendarController::class, 'listCalendarUser']);
