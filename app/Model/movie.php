<?php

namespace App\Model;

use Core\DB\ORM\Model;
use PDO;

class movie extends Model
{
    public static string $tableName = "movies";

    public static function getMovieAll()
    {
       $pdo = static::connection();

       $sql = "SELECT m.id, m.name, m.slug, m.trailer_link, m.image, m.rating,m.year, p.name AS producer, d.name 
        AS director, GROUP_CONCAT(a.name SEPARATOR ', ') AS actors FROM movies m
        LEFT JOIN producers p ON p.id = m.producer_id
        LEFT JOIN directors d ON d.id = m.director_id
        LEFT JOIN movie_actor ma ON ma.movie_id = m.id
        LEFT JOIN actors a ON a.id = ma.actor_id
        GROUP BY m.id";
       return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}