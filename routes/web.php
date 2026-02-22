<?php

use Illuminate\Support\Facades\Route;

Route::get('/',[App\Http\Controllers\ApiController::class,'index']);
Route::get('/home',function(){
    return view('dashboard');
});
Route::get('/systems',[App\Http\Controllers\ApiController::class,'getSingleSystem'])->name('system');
Route::get('/countries',[App\Http\Controllers\ApiController::class,'index']);
Route::get('/',[App\Http\Controllers\ApiController::class,'index']);


Route::get('/getAssessmentsBySystem',[App\Http\Controllers\ApiController::class,'getAssessmentsBySystem'])->name('getAssessmentsBySystem');
