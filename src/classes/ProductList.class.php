<?php

class ProductList extends Product {
    public $size;
    public $weight;
    public $height;
    public $width;
    public $length;
    public $category_name;

    public function __construct($args=[]) {
        $this->id = $args['product_id'] ?? '';
        $this->sku = $args['product_sku'] ?? '';
        $this->name = $args['product_name'] ?? '';
        $this->price = $args['product_price'] ?? '';
        $this->category_id = $args['category_id'] ?? '';
        $this->size = $args['product_size'] ?? '';
        $this->weight = $args['product_weight'] ?? '';
        $this->height = $args['product_height'] ?? '';
        $this->width = $args['product_width'] ?? '';
        $this->length = $args['product_length'] ?? '';
        $this->category_name = $args['category_name'] ?? '';
    }

    public function preview() {

        switch ($this->category_name) {
            case "DVD":
                $product_img = "movie.svg";
                $product_specs = "Size: ".$this->size." MB";
                break;
            case "Book":
                $product_img = "book.png";
                $product_specs = "Weight: ".$this->weight." kg";
                break;
            case "Furniture":
                $product_img = "furniture.svg";
                $product_specs = "Dimension: ";
                $product_specs .= $this->height."x".$this->width."x".$this->length;
                break;
            default:
                $product_img = "";
                $product_specs = "";
        }

        return 
        "   
            <input type='checkbox' id='{$this->id}' class='delete-checkbox'
             name='product[{$this->name}]' value='{$this->id}'>
            <img src='./images/{$product_img}'>
            <p>{$this->sku}</p>
            <p><b>{$this->name}</b></p>
            <p>{$this->price} $</p>
            <p>{$product_specs}</p>    
        ";
    }
    
}

?>