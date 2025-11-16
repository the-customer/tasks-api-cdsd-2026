<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PublicTaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskShareController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Authentication
Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/register',[AuthController::class,'register']);
//
// Route::post('/auth/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
//
// Route::controller(AuthController::class)->middleware('auth:sanctum')->group(function(){
//     Route::post('/auth/logout','logout');
//     Route::get('/auth/user','user');
// })
//
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/auth/logout',[AuthController::class,'logout']);
    Route::post('/auth/logout-all',[AuthController::class,'logoutAll']);//optional
    Route::get('/auth/me',[AuthController::class,'me']);
    //
    Route::get('/tasks',[TaskController::class,'index']);
    Route::post('/tasks',[TaskController::class,'store']);
    Route::get('/tasks/{task}',[TaskController::class,'show'])->can('view', 'task');
    Route::put('/tasks/{task}',[TaskController::class,'update'])->can('update', 'task');
    Route::patch('/tasks/{task}/archive',[TaskController::class,'archive'])->can('archive', 'task');
    Route::patch('/tasks/{task}/restore',[TaskController::class,'restore'])->can('archive', 'task');
    Route::patch('/tasks/{task}/visibility',[TaskController::class,'visibility'])->can('changeVisibility', 'task');
    //
    Route::post('/tasks/{task}/share',[TaskShareController::class,'store'])->can('share', 'task');
    Route::delete('/tasks/{task}/share/{user}',[TaskShareController::class,'destroy'])->can('share', 'task');

    //
    Route::post('/tasks/{task}/invite',[InvitationController::class,'create'])->can('invite', 'task');
});

// Public endpoints
Route::get('/public/tasks/{slug}',[PublicTaskController::class,'show']);
Route::post('/invitations/{token}/accept',[InvitationController::class,'accept']);
