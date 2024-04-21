<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\ResponsesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/v1/auth/login", [AuthController::class, "login"]);
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/v1/auth/logout", [AuthController::class, "logout"]);
    Route::resource("/v1/forms", FormsController::class);
    Route::resource("/v1/forms/{slug}/questions", QuestionsController::class);
    Route::resource("/v1/forms/{slug}/responses", ResponsesController::class);
});

//handle invalid token
Route::get("/authenticate", function () {
    return response()->json(["message" => "authenticated"], 401);
})->name("login");
