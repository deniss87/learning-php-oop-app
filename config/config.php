<?php
require __DIR__ . '/env.php';
loadEnv(__DIR__ . '/../.env');

// define const
define("DB_SERVER", $_ENV['DB_SERVER']);
define("DB_USER",   $_ENV['DB_USER']);
define("DB_PWD",    $_ENV['DB_PWD']);
define("DB_NAME",   $_ENV['DB_NAME']);