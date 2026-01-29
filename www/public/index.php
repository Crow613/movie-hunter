<?php
require __DIR__ . '/../Bootstrap/app.php';

$appConf = [
  "web" =>  ROOT_DIR."/routes/web.php",
];
routes($appConf);