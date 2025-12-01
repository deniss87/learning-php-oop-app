<?php

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/../src/Helpers/functions.php');


// Autoload class definitions
function my_autoload($class) {
    include(__DIR__ . '/../src/classes/' . $class . '.class.php');
}
spl_autoload_register('my_autoload');

$db = new Database(DB_SERVER, DB_NAME, DB_USER, DB_PWD);
DatabaseObject::set_database($db::connection());


?>