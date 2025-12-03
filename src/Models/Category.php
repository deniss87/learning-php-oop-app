<?php

namespace App\Models;

use App\Services\DatabaseObject;

class Category extends DatabaseObject
{
    protected static $table_name = 'Category';
    protected static $db_columns = ['category_id', 'category_name'];

    public $id;
    public $name;

    public function __construct($args = [])
    {
        $this->id = $args['category_id'] ?? '';
        $this->name = $args['category_name'] ?? '';
    }

    public function getName()
    {
        return $this->name;
    }

    public static function getAll()
    {
        $sql = "SELECT * FROM " . static::$table_name;
        return static::findBySQL($sql, [], true);
    }

    public static function getCategoryName($id_value)
    {
        $category_obj = self::findById('category_id', number_format($id_value));
        if (!$category_obj) {
            return false;
        }

        $name = $category_obj->name;
        $map = [
            'DVD' => \App\Models\DVD::class,
            'Book' => \App\Models\Book::class,
            'Furniture' => \App\Models\Furniture::class,
        ];

        return $map[$name] ?? false;
    }
}
