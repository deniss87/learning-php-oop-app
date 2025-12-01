<?php

class DVD extends Product {
    public $size;

    public function __construct($args=[]) {
        $this->size = $args['product_size'] ?? '';
        self::$db_columns[] = 'product_size';
    }


    static public function find_all() {
        $sql = "SELECT * FROM ". static::$table_name." WHERE `category_id` = '1'";
        return static::find_by_sql($sql);
    }

} // end

?>