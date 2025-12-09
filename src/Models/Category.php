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
        return static::findBySQL($sql, [], false);
    }

    public static function getCategoryName($id_value)
    {
        $sql = "SELECT category_name 
            FROM " . static::$table_name . " 
            WHERE category_id = :id 
            LIMIT 1";

        $data =  static::findBySQL($sql, ['id' => $id_value]);

        if (empty($data)) {
            return null;
        }

        return $data[0]['category_name'];
    }
}
