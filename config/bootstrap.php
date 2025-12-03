<?php

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../src/Helpers/functions.php');
use App\Services\Database;
use App\Services\DatabaseObject;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = new Database($_ENV['DB_SERVER'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PWD']);
DatabaseObject::setDB($db::connection());
