<?php
use Core\Router\Route;

Route::get('/', [\App\Http\Controllers\ResourceController::class, "index"]);
Route::get('/movie/{id}', [\App\Http\Controllers\ResourceController::class, "movie"]);
Route::get('/director/{id}', [\App\Http\Controllers\ResourceController::class, "director"]);
Route::get('/producer/{id}', [\App\Http\Controllers\ResourceController::class, "producer"]);
Route::get('/actor/{id}', [\App\Http\Controllers\ResourceController::class, "actor"]);
