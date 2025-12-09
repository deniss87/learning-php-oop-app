<?php

namespace App\Services;

use PDO;
use PDOException;
use App\Models\DVD;
use App\Models\Book;
use App\Models\Furniture;

abstract class DatabaseObject
{
    protected static $db;
    protected static $table_name = '';
    protected static $primary_key = 'id';
    protected static $db_columns = [];
    public $errors = [];

    public static function setDB(PDO $pdo)
    {
        static::$db = $pdo;
    }

    public function __construct($args = [])
    {
        foreach (static::$db_columns as $col) {
            $this->$col = $args[$col] ?? null;
        }
    }

    // Find record by ID
    public static function findById(string $id_column, int $id_value, bool $asObject = false)
    {
        $sql = "SELECT Products.*, Category.category_name
                FROM " . static::$table_name . "
                LEFT JOIN Category 
                ON Products.category_id = Category.category_id
                WHERE " . $id_column . " = :id";

        $data =  static::findBySQL($sql, [$id_value], $asObject);

        return $data;
    }

    // GET ALL RECORDS
    public static function getAll()
    {
        $sql = "SELECT Products.*, Category.category_name
        FROM " . static::$table_name . "
        LEFT JOIN Category 
        ON Products.category_id = Category.category_id";
        return static::findBySQL($sql);
    }


    // Find by SQL statement

    protected static function findBySQL(string $sql, array $params = [], bool $asObjects = false)
    {
        try {
            $stmt = static::$db->prepare($sql);
            $stmt->execute($params);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$rows) {
                return [];
            }

            if ($asObjects) {
                return array_map(function ($row) {
                    $className = "\\App\\Models\\" . $row['category_name'];
                    return new $className($row);
                }, $rows);
            }

            return $rows;
        } catch (PDOException $e) {
            throw new \Exception("Database query error: " . $e->getMessage());
        }
    }

    // Save or Update record
    public function save()
    {
        if ($this->{static::$primary_key}) {
            return $this->update();
        }
        return $this->create();
    }

    // INSERT record
    protected function create()
    {
        $attributes = $this->attributes();
        unset($attributes['category_name']);
        $cols = array_keys($attributes);
        $placeholders = array_fill(0, count($cols), '?');

        $sql = "INSERT INTO " . static::$table_name . " ("
            . implode(', ', $cols)
            . ") VALUES ("
            . implode(', ', $placeholders)
            . ")";

        try {
            $stmt = static::$db->prepare($sql);
            $stmt->execute(array_values($attributes));

            $this->{static::$primary_key} = static::$db->lastInsertId();
            return true;
        } catch (PDOException $e) {
            throw new \Exception("Create error: " . $e->getMessage());
        }
    }

    // UPDATE record
    protected function update()
    {
        $attributes = $this->attributes();
        unset($attributes['category_name']);
        $cols = array_keys($attributes);
        $assignments = implode(', ', array_map(fn($col) => "$col = ?", $cols));

        $sql = "UPDATE " . static::$table_name
            . " SET $assignments WHERE " . static::$primary_key . " = ? LIMIT 1";

        try {
            $stmt = static::$db->prepare($sql);
            return $stmt->execute([
              ...array_values($attributes),
              $this->{static::$primary_key}
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Update error: " . $e->getMessage());
        }
    }

    // DELETE record by IDs

    public static function deleteByIds(array $ids): int
    {

        if (empty($ids)) {
            return 0;
        }

        $ids = array_values(array_filter(array_map('intval', $ids), fn($v) => $v > 0));
        if (empty($ids)) {
            return 0;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM " . static::$table_name . " WHERE " . static::$primary_key . " IN ($placeholders)";

        $stmt = static::$db->prepare($sql);
        $ok = $stmt->execute($ids);
        if ($ok) {
            return $stmt->rowCount();
        }

        return 0;
    }

    // GET ATTRIBUTES
    protected function attributes()
    {
        $attrs = [];
        $columns = method_exists(get_called_class(), 'getColumns') ? get_called_class()::getColumns() : static::$db_columns;

        foreach ($columns as $col) {
            if ($col === static::$primary_key) {
                continue;
            }
            $attrs[$col] = $this->$col ?? null;
        }
        return $attrs;
    }
}
