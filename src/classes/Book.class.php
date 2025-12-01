<?php

class Book extends Product {
    public $weight;

    public function __construct($args=[]) {
        $this->weight = $args['product_weight'] ?? '';
        self::$db_columns[] = 'product_weight';
    }

    static public function find_all() {
        $sql = "SELECT * FROM ". static::$table_name." WHERE `category_id` = '2'";
        return static::find_by_sql($sql);
    }

}

?>