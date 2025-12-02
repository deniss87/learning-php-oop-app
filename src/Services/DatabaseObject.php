<?php
namespace App\Services;
use PDO;
use PDOException;

abstract class DatabaseObject {

    protected static $db;
    protected static $table_name = '';
    protected static $primary_key = 'id';
    protected static $db_columns = [];
    public $errors = [];

    public static function set_database(PDO $pdo) {
      static::$db = $pdo;
    }

    public function __construct($args = []) {
      foreach(static::$db_columns as $col) {
          $this->$col = $args[$col] ?? null;
      }
    }

    // Find record by ID
    static public function find_by_id($id_column, $id_value) {
      $sql = "SELECT * FROM " . static::$table_name;
      $sql .= " WHERE " . $id_column . " = :id";
      $stmt = self::$db->prepare($sql);
      $stmt->bindValue(':id', $id_value);
      $stmt->execute();
      $obj_array = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      if(!empty($obj_array)) {
          $class_name = get_called_class();
          return new $class_name($obj_array[0]);
      } else {
          return false;
      }
    }

    // Find ALL records
    public static function find_all() {
      $sql = "SELECT * FROM " . static::$table_name;
      return static::find_by_sql($sql);
    }


    // Find by SQL statement

    protected static function find_by_sql(string $sql, array $params = []) {
      try {
        $stmt = static::$db->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) return [];

        $class = get_called_class();
        return array_map(fn($row) => new $class($row), $rows);

      } catch (PDOException $e) {
          throw new \Exception("Database query error: " . $e->getMessage());
      }
    }

    // Save or Update record
    public function save() {
      if ($this->{static::$primary_key}) {
          return $this->update();
      }
      return $this->create();
    }

    // INSERT record
    protected function create() {
      $attributes = $this->attributes();
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
    protected function update() {
      $attributes = $this->attributes();
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

    // DELETE record
    public function delete() {
      $id = $this->{static::$primary_key};

      if (!$id) {
          throw new \Exception("Cannot delete object without ID.");
      }

      $sql = "DELETE FROM " . static::$table_name . " WHERE " . static::$primary_key . " = ? LIMIT 1";

      try {
          $stmt = static::$db->prepare($sql);
          return $stmt->execute([$id]);

      } catch (PDOException $e) {
          throw new \Exception("Delete error: " . $e->getMessage());
      }
    }

    // GET ATTRIBUTES
    protected function attributes() {
      $attrs = [];
      $columns = method_exists(get_called_class(), 'getColumns') ? get_called_class()::getColumns() : static::$db_columns;

      foreach($columns as $col) {
          if ($col === static::$primary_key) continue;
          $attrs[$col] = $this->$col ?? null;
      }
      return $attrs;

    }

}