<?php

session_start();
require_once __DIR__ . '/../../config/bootstrap.php';

use App\Models\Product;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../");
    exit;
}

if (empty($_POST['product']) || !is_array($_POST['product'])) {
    $_SESSION['error'] = "Please select products.";
    header("Location: ../");
    exit;
}

$ids = array_map('intval', $_POST['product']);
$ids = array_values(array_filter($ids, fn($v) => $v > 0));

if (empty($ids)) {
    $_SESSION['error'] = "Please select products.";
    header("Location: ../");
    exit;
}

try {
    $deleted = Product::deleteByIds($ids);
    $_SESSION['success'] = "{$deleted} product(s) deleted.";
} catch (Exception $e) {
    $_SESSION['error'] = "Failed to delete products: " . $e->getMessage();
}

header("Location: ../");
exit;
