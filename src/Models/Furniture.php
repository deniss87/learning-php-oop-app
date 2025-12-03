<?php

namespace App\Models;

class Furniture extends Product
{
    public $product_height;
    public $product_width;
    public $product_length;

    protected static $db_columns = [
        'product_id',
        'product_sku',
        'product_name',
        'product_price',
        'category_id',
        'product_height',
        'product_width',
        'product_length'
    ];

    public function __construct($args = [])
    {
        parent::__construct($args);
        $this->product_height = $args['product_height'] ?? null;
        $this->product_width = $args['product_width'] ?? null;
        $this->product_length = $args['product_length'] ?? null;
    }

    public static function getColumns(): array
    {
        return static::$db_columns;
    }

    public static function findAll()
    {
        $sql = "SELECT * FROM " . static::$table_name . " WHERE `category_id` = '3'";
        return static::findBySQL($sql);
    }
}
