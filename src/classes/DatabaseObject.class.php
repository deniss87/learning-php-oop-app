<?php

abstract class DatabaseObject {

    static protected $database;
    static protected $table_name = "";
    // static protected $columns = [];

    static public function set_database($database) {
        self::$database = $database;
    }
    static public function find_by_sql($sql) {
        $stmt= self::$database->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($result)) {
          return false;
        } 

        // results into objects
        $object_array  = [];
        $class_name = get_called_class();

        for ($i=0; $i<count($result); $i++) {
            $object_arg = [];
            foreach ($result[$i] as $key => $value) {
                $object_arg += [$key => $value];
            }
            $object_array[] = new $class_name($object_arg);
        }

        return $object_array;  
    } 
    static public function find_all() {
            $sql = "SELECT * FROM ". static::$table_name;
            return static::find_by_sql($sql);
    }
    static public function find_by_id($id_column, $id_value) {
      $sql = "SELECT * FROM " . static::$table_name . "  ";
      $sql .= "WHERE ".$id_column."='".$id_value."'";
      $obj_array = static::find_by_sql($sql);
  
      if(!empty($obj_array)) {
        return array_shift($obj_array);
      }
      else {
        return false;
      }
    }
    static public function count() {

        $sql = "SELECT COUNT(*) FROM ". static::$table_name;
        $stmt= self::$database->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return array_shift($result);
    }
    static public function create() {

        $attributes = self::attributes();

        // Pepare SQL statement
        $sql = "INSERT INTO ".static::$table_name." (";
        $sql .= join(', ', array_keys($attributes));
        $sql .= ") VALUES (:";
        $sql .= join(', :', array_keys($attributes));
        $sql .= ")";

        // Create record in database
        try {
          $stmt = self::$database->prepare($sql);
          $stmt->execute($attributes);
          $_SESSION['success'] = "New product has been added";
          return true;
        }
        catch (PDOException $e){
            die("Failed to add product: " . $e->getMessage());
        }
    }
    protected function update() {
      $this->validate();
      if(!empty($this->errors)) { return false; }
  
      $attributes = $this->sanitized_attributes();
      $attributes_pairs = [];
      foreach($attributes as $key => $value) {
        $attributes_pairs[] = "{$key}='{$value}'";
      }
  
      $sql = "UPDATE ".static::$table_name." SET ";
      $sql .= join(', ', $attributes_pairs);
      $sql .= " WHERE id='" . self::$database->escape_string($this->id) . "'";
      $sql .= "LIMIT 1";
      $result = self::$database->query($sql);
      return $result;
    }
    public function save() {
        // A new record will not have an ID 
        if(isset($this->id)) {
          return $this->update();
        } else {
          return $this->create();
        }
    }
    static public function delete() {

      $sql = "DELETE FROM ".static::$table_name." ";
      $sql .= "WHERE product_id IN ('";
      $sql .= join("', '", array_values($_POST['product']));
      $sql .= "')";
      $count = count($_POST['product']);

      // Delete record in database
      try {
        $stmt = self::$database->prepare($sql);
        $stmt->execute();
        if ($count > 1) {
          $_SESSION['success'] = $count." products were deleted";
          
        } else {
          $_SESSION['success'] = "One product was deleted";
        }
        return true;
      }
      catch (PDOException $e){
          die("Failed to delete product: " . $e->getMessage());
      }
      
    }
      // Properties which have database columns, excluding ID
    static public function attributes() {
      $attributes = [];
      $post_array = $_POST['product'];
      print_r($post_array);
      foreach($post_array as $key=> $value) {
          if (array_search($key, static::$db_columns)) {
              if ($value !== '') {
                  $attributes[$key] = $value;
              }
              else {
                  $attributes[$key] = '';
              }
          }
      }
      return $attributes;
    }


} // end

?>