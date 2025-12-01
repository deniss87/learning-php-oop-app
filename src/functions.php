<?php

function flashMessages() {
    if ( isset($_SESSION["success"]) ) {
        echo('<div class="message-success"><p>'.htmlentities($_SESSION["success"])."</p></div>\n");
        unset($_SESSION["success"]);
    }
    if ( isset($_SESSION["error"]) ) {
        echo('<div class="message-error"><p>'.htmlentities($_SESSION["error"])."</p></div>\n");
        unset($_SESSION["error"]);
    }
}
function array_empty_val($array) {
    $result = [];
    foreach ($array as $key => $value) {
        if ($value !== '') {
            $result[$key] = $value;
        }
    }
    return $result;
}

?>