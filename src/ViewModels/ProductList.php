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
            "App\Models\DVD" => "Size: {$this->product_size} MB",
            "App\Models\Book" => "Weight: {$this->product_weight} kg",
            "App\Models\Furniture" =>
                "Dimension: {$this->product_height}x{$this->product_width}x{$this->product_length}",
            default => "",
        };
    }

    public function getImage(): string
    {
        return match ($this->category_name) {
            "App\Models\DVD" => "movie.svg",
            "App\Models\Book" => "book.png",
            "App\Models\Furniture" => "furniture.svg",
            default => "no-product-found.png",
        };
    }

    public function preview(): string
    {
        $image = $this->getImage();
        $specs = $this->getSpecs();

        return "
            <input type='checkbox' id='{$this->product_id}' class='delete-checkbox'
            name='product[]' value='{$this->product_id}'>
            <img src='./images/{$image}'>
            <p>SKU: {$this->product_sku}</p>
            <p><b>{$this->product_name}</b></p>
            <p>{$this->product_price} $</p>
            <p>{$specs}</p>    
        ";
    }
}
