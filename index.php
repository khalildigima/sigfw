<?php

require_once __DIR__ . "/vendor/autoload.php";

use Sigfw\Service\Config;
use Sigfw\Service\Connection;
use Sigfw\Service\Logger;

$_config = array(
    "host" => "localhost",
    "username" => "root",
    "password" => "1",
    "dbname" => "api_sfw"
);

$config = new Config();
$config->set_development_config($_config);

$connection = new Connection($config);
$sql = "Select * From `posts`";
$stmt = $connection->raw_query($sql);
if($stmt)
{
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();
}