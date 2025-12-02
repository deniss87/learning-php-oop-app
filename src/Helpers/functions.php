<?php
use App\Models\Product;
use App\Models\DVD;
use App\Models\Book;
use App\Models\Furniture;
use App\ViewModels\ProductList;

function flashMessages() {
    if ( isset($_SESSION["success"]) ) {
        echo('<div class="message-success"><p>'.htmlentities($_SESSION["success"])."</p></div>\n");
        unset($_SESSION["success"]);
    }
    if ( isset($_SESSION["error"]) ) {
        echo('<div class="message-error"><p>'.htmlentities($_SESSION["error"])."</p></div>\n");
        unset($_SESSION["error"]);
    }
}
function array_empty_val($array) {
    $result = [];
    foreach ($array as $key => $value) {
        if ($value !== '') {
            $result[$key] = $value;
        }
    }
    return $result;
}

function getSortedProductList(): array
{
    $sortParam = $_GET['sort'] ?? 'new';
    $sort = null;
    $order = null;

    switch ($sortParam) {
        case 'old':
            $sort = 'product_id';
            $order = 'ASC';
            break;
        case 'price_up':
            $sort = 'product_price';
            $order = 'ASC';
            break;
        case 'price_down':
            $sort = 'product_price';
            $order = 'DESC';
            break;
        default:
            $sort = 'product_id';
            $order = 'DESC';
            break;
    }

    $productListData = Product::all($sort, $order);

    $typedProducts = array_map(
        fn($row) => Product::createByCategory($row),
        $productListData
    );

    $productObjects = array_map(
        fn($p) => new ProductList([
            'product_id'    => $p->product_id,
            'product_sku'   => $p->product_sku,
            'product_name'  => $p->product_name,
            'product_price' => $p->product_price,
            'category_id'   => $p->category_id,
            'category_name' => $p->category_name,
            'product_size'   => $p->product_size ?? null,
            'product_weight' => $p->product_weight ?? null,
            'product_height' => $p->product_height ?? null,
            'product_width'  => $p->product_width ?? null,
            'product_length' => $p->product_length ?? null,
        ]),
        $typedProducts
    );

    return $productObjects;
}