<?php

require_once __DIR__ . "/vendor/autoload.php";

use Sigfw\Model\Posts;
use Sigfw\Service\Utils;
use Sigfw\Service\Logger;

$model = new Posts();
$result = $model->get_custom();
Utils::dump($result);