<?php

use Illuminate\Support\Facades\Route;

Route::get('/',[App\Http\Controllers\ApiController::class,'index'])->name('home');

Route::get('/systems',[App\Http\Controllers\ApiController::class,'getSingleSystem'])->name('system');
Route::get('/countries',[App\Http\Controllers\ApiController::class,'getSingleCountry'])->name('country');
Route::get('/dettaglio-specie',[App\Http\Controllers\ApiController::class,'getTaxaSis'])->name('taxasis');
Route::get('/dettaglio-valutazione',[App\Http\Controllers\ApiController::class,'getSingleAssessment'])->name('valutazione');

