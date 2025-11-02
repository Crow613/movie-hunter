<?php

namespace App\Http\Controllers\Api;

use App\Model\movie;

class MovieApiController
{
    public static function getLatest()
    {
        $movie = new movie();
        $movieLatest = $movie->select()
                             ->orderBy("created_at","DESC")
                             ->limit(6)->get();
        return $movieLatest;
    }
    public static function getTop()
    {

    }
    public static function getMostCommented()
    {

    }
}