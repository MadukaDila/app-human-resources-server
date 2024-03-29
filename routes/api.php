<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DuplicatesController;

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

// Grouping routes under the AttendanceController
Route::controller(AttendanceController::class)->group(function (){

    // Endpoint for uploading attendance data via POST request
    Route::post('/uploadAttendanceRecords', 'uploadAttendanceRecords');

    // Endpoint for retrieving employee attendance information via GET request
    Route::get('/fetchEmployeeAttendance', 'fetchEmployeeAttendance');

});


Route::post('/duplicate', [DuplicatesController::class, 'duplicates']);