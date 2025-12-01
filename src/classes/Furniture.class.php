<?php

class Furniture extends Product {
    public $height;
    public $width;
    public $length;

    public function __construct($args=[]) {
        $this->height = $args['product_height'] ?? '';
        $this->width = $args['product_width'] ?? '';
        $this->length = $args['product_length'] ?? '';

        array_push(self::$db_columns, "product_height", "product_width", "product_length");
    }

    static public function find_all() {
        $sql = "SELECT * FROM ". static::$table_name." WHERE `category_id` = '3'";
        return static::find_by_sql($sql);
    }
    
}

?>