<?php

namespace App\Http\Controllers\Api;

use App\Model\Produсer;

class ProducerApiController
{
    /**
     * @param $id
     * @return array
     */
    public static function getMoviesById($id): array
    {
        return (new Produсer())->getMoviesAndProducerById($id);
    }
}