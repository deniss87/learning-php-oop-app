<?php

session_start();
require_once __DIR__ . '/../../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../");
    exit;
}

if (empty($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error'] = "Invalid CSRF token.";
    header("Location: ../");
    exit;
}

if (empty($_POST['product']) || !is_array($_POST['product'])) {
    $_SESSION['error'] = "Please select one product to edit.";
    header("Location: ../");
    exit;
}

$ids = array_map('intval', $_POST['product']);
$ids = array_values(array_filter($ids, fn($v) => $v > 0));

if (count($ids) !== 1) {
    $_SESSION['error'] = "Please select exactly one product to edit.";
    header("Location: ../");
    exit;
}

$id = $ids[0];

header("Location: ../edit-product/?id=" . $id);
exit;
