<?php

namespace App\Model;

use Core\DB\ORM\Model;

class Movie extends Model
{
    /**
     * @var string
     */
    public static string $tableName = "movies";
    /**
     * @var false|string
     */
    public static false|string $tableNameAlias = "m";


    /**
     * @param  string  $name
     * @param  int  $limit
     * @return array
     */
    public function findMovieByName(string $name, int $limit = 0): array
    {
        return $this->select("m.id, m.name, m.movie_link,m.image, m.rating, m.created_at ")
                    ->whereLike("name", "%$name%")
                    ->limit($limit)
                    ->get() ?? [];
    }

    /**
     * @param  string  $sortBy
     * @param  string  $sortType
     * @param  int  $limit
     * @return array
     */
    public function getOrderBy(string $sortBy, string $sortType = "DESC", int $limit = 6): array
    {
        return $this->select("m.id, m.name, m.movie_link, m.image, m.rating, m.created_at ")
              ->groupBy("m.id")
              ->orderBy("m.".$sortBy, $sortType)
              ->limit((string)$limit)
              ->get() ?? [];
    }
    /**
     * @param $id
     * @return array
     */
    public function getMovieById($id): array
    {
        $movie = $this->select("m.id, m.name, m.image, m.movie_link, m.rating ")
        ->where("m.id", "=", $id)
        ->get()[0] ?? null;
        if (!$movie) return [];

        $director = $this->getDirectorsByMovieId($id);
        $producers = $this->getProducersByMovieId($id);
        $actors = $this->getActorsByMovieId($id);

        return [
          "id" => $movie["id"],
          "name" => $movie["name"],
          "image" => $movie["image"],
          "movie_link" => $movie["movie_link"],
          "rating" => $movie["rating"],
          "director_id" => $director["id"],
          "director_name"=>$director["name"],
          "producers" => $producers,
          "actors" => $actors
        ];
    }
    /**
     * @param $id
     * @return array
     */
    public function getProducersByMovieId($id): array
    {
        return $this->select(" p.id, p.name ")
        ->join(" LEFT JOIN movie_producer mp ON mp.movie_id = m.id ")
        ->join(" LEFT JOIN producers p ON p.id = mp.producer_id ")
        ->where("m.id", "=", $id)
        ->get();
    }

    /**
     * @param $id
     * @return array
     */
    public function getDirectorsByMovieId($id): array
    {
        return $this->select("d.id, d.name")
                    ->join(" LEFT JOIN movie_director md ON md.movie_id = m.id ")
                    ->join(" LEFT JOIN directors d ON d.id = md.director_id ")
                    ->where("m.id","=",$id)
                    ->get()[0] ?? [];
    }
    /**
     * @param $id
     * @return array
     */
    public function getActorsByMovieId($id): array
    {
        return $this->select(" a.id, a.name")
        ->join(" LEFT JOIN movie_actor ma ON ma.movie_id = m.id ")
        ->join(" LEFT JOIN actors a ON a.id = ma.actor_id ")
        ->where("m.id","=",$id)
        ->get();
    }

}