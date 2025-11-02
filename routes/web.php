<?php
use Core\Router\Route;

Route::get('/site', [\App\Http\Controllers\ResourceController::class, "index"]);