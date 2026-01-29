<?php
namespace Core\Router;

class Route
{
    public static function get(string $url, array | callable $params)
    {
        if (empty($url)) throw new \Exception("$url not defined");
        if (!$params) throw new \Exception("$params not defined");

        $explode = explode("/", $url);
        $subj = array_pop($explode);
        $queryParam = ["{id}"=>"/^[0-9]+$/"];
        $explodeRequest = explode("/", $_SERVER['REQUEST_URI']);
        $paramUrl = array_pop($explodeRequest);
        $checkParam = false;
        if ($subj){
           $checkParam =  preg_match($queryParam[$subj], $paramUrl, $matches );
            if($checkParam){
                $param = $matches[0];
                $url = implode("/", $explode)."/".$param;
            }
        }
        if (($_SERVER['REQUEST_METHOD'] === "GET" || $checkParam ) && $_SERVER['REQUEST_URI'] === $url ) {
            if (is_array($params)){
                [$class, $method] = $params;
                if (method_exists($class, $method)){
                    if (!empty($param)) return $class::$method($param);
                    return $class::$method();
                }
            }else return $params();

        }else return new  static;
    }

}