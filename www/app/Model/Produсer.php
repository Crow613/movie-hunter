<?php

namespace App\Model;

use Core\DB\ORM\Model;

class Produсer extends Model
{
    /**
     * @var string
     */
    public static  string $tableName = "producers";
    /**
     * @var string|false
     */
    public static  string|false $tableNameAlias= "p";

    /**
     * @param $id
     * @return array
     */
    public function getMoviesAndProducerById($id): array
    {
        $producer = $this->select(" p.id, p.image, p.name, p.bio ")
                         ->where("p.id", "=", $id)
                         ->get()[0] ?? null;
        if (!$producer) return [];

        $movies = $this->findMoviesById($id);
        return [
          "id" => $producer["id"],
          "name" => $producer["name"],
          "image" => $producer["image"],
          "bio" => $producer["bio"],
          "movies"=> $movies,
        ];
    }

    /**
     * @param $id
     * @return array
     */
    public function findMoviesById($id): array
    {
        return $this->select(" m.id, m.name, m.image ")
                    ->join(" LEFT JOIN movie_producer mp ON mp.producer_id = p.id ")
                    ->join(" LEFT JOIN movies m ON m.id = mp.movie_id ")
                    ->where("p.id", "=", $id)
                    ->get();
    }

}