<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\VerifySignature;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/transaction', [TransactionController::class, 'store'])
    ->middleware(VerifySignature::class);
Route::get('/transaction', [TransactionController::class, 'list']);
