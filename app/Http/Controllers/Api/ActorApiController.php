<?php

namespace App\Http\Controllers\Api;

use App\Model\Actor;

class ActorApiController
{
    /**
     * @param $id
     * @return array
     */
    public static function getMoviesById($id)
    {
        return ( new Actor())->getMoviesAndActorById($id);
    }

}