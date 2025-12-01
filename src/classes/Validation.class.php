<?php

class Validation {

    public function __construct(){
        $this->args = $_POST['product'];
    }

    public function getData() {
        return $this->args;
    }
    public function validate() {
        $attributes = $this->args;
        $db_columns = Product::getColumns();

        foreach($attributes as $key => $value) {
            if (array_search($key, $db_columns)) {
                if ($value == '') {
                    $_SESSION['error'] = "Please fill '".strtoupper($key)."' field";
                    return false;
                }
                if ($key = 'category_id' && $value == '0') {
                    $_SESSION['error'] = "Please choose 'Product category'";
                    return false;
                }
            }
        }
        
        $category_name = Category::getCategoryName($attributes['category_id']);
        $category_obj = new $category_name;
        $db_columns = $category_name::getColumns();

        foreach($attributes as $key => $value) {
            if (array_search($key, $db_columns)) {
                if ($value == '') {
                    $_SESSION['error'] = "Please fill '".strtoupper($key)."' field";
                    return false;
                }
            }
        }

        
        // Check in Database if SKU value alreay exist
        
        $sql = "SELECT * FROM Products";
        $sql .= " WHERE product_sku='".$attributes['product_sku']."'"; 
        
        $result = Product::find_by_sql($sql);
        if ($result !== false) {
            $_SESSION['error'] = "This SKU already exists";
            return false;
        }        

        return true;
    }
}

?>