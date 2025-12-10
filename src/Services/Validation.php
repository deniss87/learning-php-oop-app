<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\DVD;

class Validation
{
    private array $args;
    private ?int $productId;

    public function __construct(array $args, ?int $productId = null)
    {
        $this->args = $args;
        $this->productId = $productId ?? null;
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
                // Empty required fields
                if ($value === '') {
                    $_SESSION['error'] = "Please fill '" . strtoupper($key) . "' field";
                    return false;
                }
                // Category not selected
                if ($key === 'category_id' && $value === '0') {
                    $_SESSION['error'] = "Please choose 'Product category'";
                    return false;
                }
            }
        }

        // Checking for negative values
        $numericFields = [
            'product_price',
            'product_size',
            'product_weight',
            'product_height',
            'product_width',
            'product_length'
        ];

        foreach ($numericFields as $field) {
            if (!isset($attributes[$field]) || $attributes[$field] === '') {
                continue;
            }

            if (!is_numeric($attributes[$field])) {
                $_SESSION['error'] = strtoupper($field) . " must be a number";
                return false;
            }

            if (floatval($attributes[$field]) < 0) {
                $_SESSION['error'] = strtoupper($field) . " must be 0 or greater";
                return false;
            }
        }

        // Check in Database if SKU value alreay exists
        if (Product::existsSKU($this->args['product_sku'], $this->productId)) {
            $_SESSION['error'] = "This SKU already exists";
            return false;
        }

        return true;
    }
}
