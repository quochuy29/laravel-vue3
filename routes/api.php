<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LeaveRequestHistoryController;
use App\Http\Controllers\RequestApproveController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
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

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('calendar', [EventController::class, 'calendar']);
Route::post('save-event', [EventController::class, 'saveEvent']);
Route::get('attendances/{date}', [CalendarController::class, 'attendances']);
Route::get('duration', [CalendarController::class, 'duration']);
Route::middleware('auth:sanctum')->get('get-list-calendar-user', [CalendarController::class, 'listCalendarUser']);
Route::middleware('auth:sanctum')->post('create-request', [RequestController::class, 'createRequest']);
Route::middleware('auth:sanctum')->get('get-calendar-by-date/{date}', [RequestController::class, 'getCalendarByDate']);
Route::middleware('auth:sanctum')->get('approver', [RequestApproveController::class, 'listApprovers']);
Route::middleware('auth:sanctum')->get('my-request', [RequestController::class, 'myRequest']);
Route::middleware('auth:sanctum')->put('approve-request/{type}/{code}', [RequestController::class, 'approveRequest']);
Route::middleware('auth:sanctum')->get('infor', [UserController::class, 'infor']);
Route::middleware('auth:sanctum')->get('my-history', [LeaveRequestHistoryController::class, 'myHistory']);
Route::middleware('auth:sanctum')->get('request-from-my-member', [RequestController::class, 'requestFromMyMember']);
