<?php

namespace App\Model;

use Core\DB\ORM\Model;

class Director extends Model
{
    /**
     * @var string
     */
    public static  string $tableName = "directors";
    /**
     * @var string|false
     */
    public static  string|false $tableNameAlias = "d";
    /**
     * @param $id
     * @return array
     */
    public function getMoviesAndDirectorById($id): array
    {
        $director = $this->select(" d.id, d.image, d.name, d.bio  ")
                             ->where("d.id", "=", $id)
                             ->get()[0] ?? null;

        if (!$director) return [];

        $movies = $this->findMoviesById($id);
        return [
          "id" => $director["id"],
          "name" => $director["name"],
          "image" => $director["image"],
          "bio" => $director["bio"],
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
                    ->join(" LEFT JOIN movie_director md ON md.director_id = d.id ")
                    ->join(" LEFT JOIN movies m ON m.id = md.movie_id ")
                    ->where("d.id", "=", $id)
                    ->get();
    }
}