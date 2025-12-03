<?php

namespace App\Models;

class Book extends Product
{
    public $product_weight;

    protected static $db_columns = [
        'product_id',
        'product_sku',
        'product_name',
        'product_price',
        'category_id',
        'product_weight'
    ];

    public function __construct($args = [])
    {
        parent::__construct($args);
        $this->product_weight = $args['product_weight'] ?? null;
    }

    public static function getColumns(): array
    {
        return static::$db_columns;
    }

    public static function getAll($sort = null, $order = null)
    {
        $sql = "SELECT * FROM " . static::$table_name . " WHERE `category_id` = '2'";
        return static::findBySQL($sql);
    }
}
