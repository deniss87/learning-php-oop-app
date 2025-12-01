<?php

class Product extends DatabaseObject {

    static protected $table_name = 'Products';
    static protected $db_columns = 
        [
         'product_id', 'product_sku', 'product_name', 'product_price', 'category_id'
        ];

    public $id; 
    public $sku;
    public $name;
    public $price;
    public $category_id;

    public function __construct($args=[]) {
      $this->id = $args['product_id'] ?? '';
      $this->sku = $args['product_sku'] ?? '';
      $this->name = $args['product_name'] ?? '';
      $this->price = $args['product_price'] ?? '';
      $this->category_id = $args['category_id'] ?? '';

    }
  
    public function getName() {
      return "{$this->name} {$this->price} {$this->sku}";
    }
    static public function find_all() {
      if (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
          case "old":
            $sortColumn="product_id";
            $sortOrder="ASC";
            break;
          case "price_up":
            $sortColumn="product_price";
            $sortOrder="ASC";
            break;
          case "price_down":
            $sortColumn="product_price";
            $sortOrder="DESC";
            break;
          default:
            $sortColumn="product_id";
            $sortOrder="DESC";
            break;
        }
      } else {
        $sortColumn="product_id";
        $sortOrder="DESC";
      }

      $sql = "SELECT Products.*, Category.category_name ";
      $sql .= " FROM ".static::$table_name;
      $sql .= " LEFT JOIN Category ON Products.category_id=Category.category_id";
      $sql .= " ORDER BY Products.".$sortColumn." ".$sortOrder;

      return static::find_by_sql($sql);
    }
    static public function getColumns() {
      return self::$db_columns;
    }

    
} // end

?>