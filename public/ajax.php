<?php
require_once "../vendor/autoload.php";

$className = $_GET['className'];
$method = $_GET['method'];
$data = $_GET['data']??[];

if (method_exists($className, $method)) {
    $class = new $className();
    if (isset($data)){
      echo json_encode($class->$method());
      die();
    }
    echo json_encode($class->$method());
    die();
}

