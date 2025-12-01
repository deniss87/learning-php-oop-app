<?php

class Category extends DatabaseObject {

    static protected $table_name = 'Category';
    static protected $db_columns = ['category_id', 'category_name'];
    
    public $id; 
    public $name;

    public function __construct($args=[]) {
      $this->id = $args['category_id'] ?? '';
      $this->name = $args['category_name'] ?? '';
    }
  
    public function name() {
      return $this->name;
    }
    static public function getCategoryName($id_value) {
        $category_obj = self::find_by_id('category_id', number_format($id_value));
        if ($category_obj) {
          return $category_obj->name();
        }
        return false;
    } 
    
} // end

?>