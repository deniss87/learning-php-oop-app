<?php

namespace App\Models;

use App\Services\DatabaseObject;
use App\Models\Category;
use App\Models\DVD;
use App\Models\Book;
use App\Models\Furniture;

class Product extends DatabaseObject
{
    protected static $table_name = 'Products';
    protected static $primary_key = 'product_id';

    protected static $db_columns = [
        'product_id',
        'product_sku',
        'product_name',
        'product_price',
        'category_id',
    ];

    public $product_id;
    public $product_sku;
    public $product_name;
    public $product_price;
    public $category_id;
    public $category_name;

    public function __construct($args = [])
    {
        parent::__construct($args);

        $this->product_id    = $args['product_id']    ?? null;
        $this->product_sku   = $args['product_sku']   ?? null;
        $this->product_name  = $args['product_name']  ?? null;
        $this->product_price = $args['product_price'] ?? null;
        $this->category_id   = $args['category_id']   ?? null;

        if (!empty($this->category_id)) {
            $category = Category::getCategoryName($this->category_id);
            $this->category_name = $category ?? null;
        }
    }

    public function getName(): string
    {
        return "{$this->product_name} {$this->product_price} {$this->product_sku}";
    }

    public static function getColumns(): array
    {
        return self::$db_columns;
    }

    public static function createByCategory(array $data)
    {

        $id = (int) ($data['category_id'] ?? 0);

        return match ($id) {
            1 => new DVD($data),
            2 => new Book($data),
            3 => new Furniture($data),
            default => new Product($data),
        };
    }

    // GET ALL PRODUCTS
    public static function getAll($sort = null, $order = null)
    {
        $allowedSort = [
            'product_id',
            'product_price'
        ];

        $allowedOrder = ['ASC', 'DESC'];

        $sortColumn = in_array($sort, $allowedSort) ? $sort : 'product_id';
        $sortOrder = in_array($order, $allowedOrder) ? $order : 'DESC';

        $sql = "SELECT Products.*, Category.category_name
                FROM " . static::$table_name . "
                LEFT JOIN Category 
                    ON Products.category_id = Category.category_id
                ORDER BY Products.$sortColumn $sortOrder";

        return static::findBySQL($sql);
    }

    public static function existsSKU(string $sku): bool
    {
        $sql = "SELECT COUNT(*) as count FROM " . static::$table_name . " WHERE product_sku = ?";
        $row = static::findBySQL($sql, [$sku]);
        $count = $row[0]['count'] ?? 0;
        return $count > 0;
    }
}
