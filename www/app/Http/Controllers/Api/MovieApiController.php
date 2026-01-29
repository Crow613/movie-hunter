<?php

namespace App\Http\Controllers\Api;

use App\Model\movie;

class MovieApiController
{

    /**
     * @param array $data
     * @return array
     */
    public static function getLatest(array $data = []): array
    {
        $limit = $data["limit"] ?? 6 ;
        $sort = 'DESC';
      return  (new Movie())->getOrderBy("created_at", $sort, $limit) ?? [];
    }

    public static function getMovieByName(array $data): array
    {
        $name = $data['name'] ?? "";

        return (new movie())->findMovieByName($name);
    }
    public static function getTop( array $data = []): array
    {
        $limit = $data["limit"] ?? 6;
        $sort = $data["sort"] ?? 'DESC';
        return  (new movie())->getOrderBy("rating", $sort, $limit) ?? [];

    }

    public static function getMoviesById($id): array
    {
        return (new movie())->getMovieById($id);
    }
    public static function getMostCommented()
    {
        $limit = $data["limit"] ?? 6;
        $sort = $data["sort"] ?? 'DESC';
        return  (new movie())->getOrderBy("rating", $sort, $limit) ?? [];
    }
}