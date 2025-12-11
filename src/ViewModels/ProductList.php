<?php

namespace App\ViewModels;

class ProductList
{
    public $product_id;
    public $product_sku;
    public $product_name;
    public $product_price;
    public $category_id;
    public $category_name;

    public $product_size;
    public $product_weight;
    public $product_height;
    public $product_width;
    public $product_length;

    public function __construct(array $args = [])
    {
        foreach ($args as $key => $value) {
            $this->$key = $value ?? null;
        }
    }

    public function getSpecs(): string
    {
        return match ($this->category_name) {
            "DVD" => "Size: {$this->product_size} MB",
            "Book" => "Weight: {$this->product_weight} kg",
            "Furniture" =>
                "Dimension: {$this->product_height}x{$this->product_width}x{$this->product_length}",
            default => "",
        };
    }

    public function getImage(): string
    {
        return match ($this->category_name) {
            "DVD" => "movie.svg",
            "Book" => "book.png",
            "Furniture" => "furniture.svg",
            default => "no-product-found.png",
        };
    }

    public function preview(): string
    {
        $id    = e($this->product_id);
        $sku   = e($this->product_sku);
        $name  = e($this->product_name);
        $price = number_format((float)$this->product_price, 2);

        $specsRaw = $this->getSpecs();
        $specs = e($specsRaw);

        $imageRaw = $this->getImage();
        $imageSafe = e($imageRaw);
        $imagePath = __DIR__ . "/../../public/images/" . $imageRaw;
        if (!file_exists($imagePath)) {
            $imageSafe = "no-product-found.png";
        }

        return "
            <label class='checkbox-wrapper'>
              <input type='checkbox' id='{$id}' class='delete-checkbox'
              name='product[]' value='{$id}'>
              <span class='checkbox-custom'></span>
            </label>
            <img src='./images/{$imageSafe}' alt='product image'>
            <p>SKU: {$sku}</p>
            <p><b>{$name}</b></p>
            <p>{$price} $</p>
            <p>{$specs}</p>    
        ";
    }
}
