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

        // Check in Database if SKU value alreay exists
        if (Product::existsSKU($this->args['product_sku'], $this->productId)) {
            $_SESSION['error'] = "This SKU already exists";
            return false;
        }

        return true;
    }
}
