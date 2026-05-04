<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HikerController;
use App\Http\Controllers\MedicalStatusController;
use App\Http\Controllers\HikingHistoryController;

// Hiker CRUD
Route::apiResource('hikers', HikerController::class);

// Medical status
Route::get('hikers/{hiker}/medical',    [MedicalStatusController::class, 'show']);
Route::post('hikers/{hiker}/medical',   [MedicalStatusController::class, 'store']);
Route::put('hikers/{hiker}/medical',    [MedicalStatusController::class, 'update']);

// Hiking history
Route::get('hikers/{hiker}/histories',  [HikingHistoryController::class, 'index']);
Route::post('hikers/{hiker}/histories', [HikingHistoryController::class, 'store']);


