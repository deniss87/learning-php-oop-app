<?php
namespace App\Models;
use App\Services\DatabaseObject;

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