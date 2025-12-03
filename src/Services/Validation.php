<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;

class Validation
{
    private array $args;

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function getData(): array
    {
        return $this->args;
    }

    public function validate(): bool
    {
        $attributes = $this->args;
        $db_columns = Product::getColumns();

        foreach ($attributes as $key => $value) {
            if (in_array($key, $db_columns, true)) {
                if ($value === '') {
                    $_SESSION['error'] = "Please fill '" . strtoupper($key) . "' field";
                    return false;
                }
                if ($key === 'category_id' && $value === '0') {
                    $_SESSION['error'] = "Please choose 'Product category'";
                    return false;
                }
            }
        }

        $categoryClass = Category::getCategoryName($attributes['category_id'] ?? 0);

        if ($categoryClass && class_exists($categoryClass)) {
            $db_columns = $categoryClass::getColumns();
            foreach ($attributes as $key => $value) {
                if (in_array($key, $db_columns, true) && $value === '') {
                    $_SESSION['error'] = "Please fill '" . strtoupper($key) . "' field";
                    return false;
                }
            }
        } else {
            $_SESSION['error'] = "Invalid category selected";
            return false;
        }


        // Check in Database if SKU value alreay exists
        if (Product::existsSKU($attributes['product_sku'])) {
            $_SESSION['error'] = "This SKU already exists";
            return false;
        }

        return true;
    }
}
