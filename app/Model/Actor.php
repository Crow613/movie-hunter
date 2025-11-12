<?php

namespace App\Model;

use Core\DB\ORM\Model;
class Actor extends Model
{
    /**
     * @var string
     */
    public static string $tableName = "actors";
    /**
     * @var string|false
     */
    public static string|false $tableNameAlias = "a";


    /**
     * @param int $id
     * @return array
     */
    public function getMoviesAndActorById(int $id): array
    {
        $actor = $this->select(" a.id, a.image, a.name, a.bio ")
                      ->where("a.id", "=", $id)
                      ->get()[0] ?? null;
        if (!$actor) return [];
        $movies = $this->findMoviesById($id);
        return [
          "id" => $actor["id"],
          "name" => $actor["name"],
          "image" => $actor["image"],
          "bio" => $actor["bio"],
          "movies"=> $movies,
        ];
    }
    /**
     * @param int $id
     * @return array
     */
    public function findMoviesById(int $id): array
    {
        return $this->select(" m.id, m.name, m.image ")
                    ->join(" LEFT JOIN movie_actor ma ON ma.actor_id = a.id ")
                    ->join(" LEFT JOIN movies m ON m.id = ma.movie_id ")
                    ->where("a.id", "=", $id)
                    ->get();
    }
}