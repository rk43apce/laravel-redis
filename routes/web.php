<?php

use App\Http\Controllers\RedisController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/redis/set', [RedisController::class, 'setValue']);
Route::get('/redis/get', [RedisController::class, 'getValue']);
Route::get('/redis/config', [RedisController::class, 'getConfig']);
Route::get('/redis/keys', [RedisController::class, 'getKeys']);
