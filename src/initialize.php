<?php

require_once('db_credential.php');
require_once('functions.php');


// -> All classes in directory
// foreach(glob('classes/*.class.php') as $file) {
// require_once($file);
// }

// Autoload class definitions
function my_autoload($class) {
//if(preg_match('/\A\w+\Z/', $class)) {
    include('classes/' . $class . '.class.php');
//}
}
spl_autoload_register('my_autoload');

$db = new Database(DB_SERVER, DB_NAME, DB_USER, DB_PWD);
DatabaseObject::set_database($db::connection());


?>