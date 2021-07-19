<?php

require_once __DIR__ . "/vendor/autoload.php";

use Sigfw\Service\Config;
use Sigfw\Service\Connection;
use Sigfw\Service\Logger;
use Sigfw\Service\Model;
use Sigfw\Service\Utils;

$model = new Model('posts');
$result = $model->get_all();
Utils::dump($result);