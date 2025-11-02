<?php
namespace Core\Router;

class Route
{
    public static function get(string $url, array | callable $params)
    {
        if (empty($url)) throw new \Exception("$url not defined");
        if (!$params) throw new \Exception("$params not defined");

        if ($_SERVER['REQUEST_METHOD'] === "GET" && $_SERVER['REQUEST_URI'] === $url) {
            if (is_callable($params)) {
                return $params();
            }
            if (is_array($params)){
                [$class, $method] = $params;
                if (method_exists($class, $method)){
                    return  $class::$method();
                }
            }
        }else{
            header("Location: /404.php");
        }
    }

}