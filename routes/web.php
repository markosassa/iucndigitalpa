<?php

use Illuminate\Support\Facades\Route;

Route::get('/',[App\Http\Controllers\ApiController::class,'index']);
Route::get('/home',function(){
    return view('dashboard');
});
Route::get('/systems/{systems}',[App\Http\Controllers\ApiController::class,'getSingleSystem'])->name('');
Route::get('/countries',[App\Http\Controllers\ApiController::class,'index']);
Route::get('/',[App\Http\Controllers\ApiController::class,'index']);
