<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ActorApiController;
use App\Http\Controllers\Api\DirectorApiController;
use App\Http\Controllers\Api\MovieApiController;
use App\Http\Controllers\Api\ProducerApiController;

class ResourceController
{

    public static function index()
    {
        return route("/movie/pages/home");
    }
    public static function movie($id)
    {
        return route("/movie/pages/movie", ["movie" => MovieApiController::getMoviesById($id)]);
    }
    public static function director($id)
    {
        return route("/movie/pages/person", ["items" => DirectorApiController::getMoviesById($id)]);
    }
    public static function producer($id)
    {
        return route("/movie/pages/person", ["items" => ProducerApiController::getMoviesById($id)]);
    }
    public static function actor($id)
    {
        return route("/movie/pages/person", ["items" => ActorApiController::getMoviesById($id)]);
    }
}