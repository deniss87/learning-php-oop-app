<?php

namespace App\Models;

class DVD extends Product
{
    public $product_size;

    protected static $db_columns = [
        'product_id',
        'product_sku',
        'product_name',
        'product_price',
        'category_id',
        'product_size'
    ];


    public function __construct($args = [])
    {
        parent::__construct($args);
        $this->product_size = $args['product_size'] ?? null;
    }

    public static function getColumns(): array
    {
        return static::$db_columns;
    }

    public static function findAll()
    {
        $sql = "SELECT * FROM " . static::$table_name . " WHERE `category_id` = '1'";
        return static::findBySQL($sql);
    }
}
