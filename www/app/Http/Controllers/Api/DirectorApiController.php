<?php

namespace App\Http\Controllers\Api;

use App\Model\Director;

class DirectorApiController
{

    /**
     * @param $id
     * @return array
     */
    public static function getMoviesById($id): array
    {
        return (new Director())->getMoviesAndDirectorById($id);
    }
}