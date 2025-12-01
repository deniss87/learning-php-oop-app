<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/env.php';
require_once(__DIR__ . '/../src/Helpers/functions.php');

use App\Services\Database;
use App\Services\DatabaseObject;

loadEnv(__DIR__ . '/../.env');
$db = new Database($_ENV['DB_SERVER'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PWD']);
DatabaseObject::set_database($db::connection());