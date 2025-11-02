<?php

namespace App\Http\Controllers;

use App\Model\movie;

class ResourceController
{
    public static function index()
    {
        return route("/movie/pages/home");
    }
}