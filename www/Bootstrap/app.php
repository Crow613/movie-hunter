<?php
require __DIR__ . '/../vendor/autoload.php';
define("ROOT_DIR", dirname(__dir__));

$appConf = [
  "web" =>  ROOT_DIR."/routes/web.php",
];

function routes($paths)
{
    foreach ($paths as $path) {
        require_once $path;
    }
}
function route($path, $arResult = []){
   require_once ROOT_DIR."/resources/view".$path.".php";
}

routes($appConf);
