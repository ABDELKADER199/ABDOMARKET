<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('locker/check-status', [LockerController::class, 'checkLockerStatus']);
Route::post('locker/{id}/close', [LockerController::class, 'closeLocker']);
Route::post('locker/open', [LockerController::class, 'openNewLocker']);
Route::post('locker/invoice', [InvoiceController::class, 'SaveInvoice']);
Route::get('locker', [LockerController::class, 'index']);
Route::get('locker/{id}', [LockerController::class, 'show']);
Route::post('locker/send-whatsapp', [InvoiceController::class, 'sendInvoice']);
Route::post('locker/upload', [InvoiceController::class, 'uploadPDF']);
Route::get('users' , [AuthController::class ,'getCurrentUser']);
Route::post('login/face', [AuthController::class, 'loginWithFace']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix("product")->group(function () {
        Route::get("/", [ProductController::class, "index"]);
        Route::get("/{id}", [ProductController::class, "show"]);
        Route::post("/", [ProductController::class, "store"]);
        Route::post("/{id}", [ProductController::class, "update"]);
        Route::delete("/{id}", [ProductController::class, "destroy"]);
    });
    Route::post('logout', [AuthController::class, 'logout']);
});
