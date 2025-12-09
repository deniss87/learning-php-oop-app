<?php
session_start();
require_once __DIR__ . '/../config/bootstrap.php';
use App\Models\Product;

$productList = getSortedProductList();

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     if (!empty($_POST['product']) && is_array($_POST['product'])) {
//         $ids = array_map('intval', $_POST['product']);
//         $ids = array_values(array_filter($ids, fn($v) => $v > 0));

//         if (empty($ids)) {
//             $_SESSION['error'] = "Please select products";
//             header("Location: ./");
//             exit;
//         }

//         try {
//             $deleted = Product::deleteByIds($ids);
//             $_SESSION['success'] = $deleted . " product(s) deleted.";
//         } catch (Exception $e) {
//             $_SESSION['error'] = "Failed to delete products: " . $e->getMessage();
//         }

//         header("Location: ./");
//         exit;
//     } else {
//         $_SESSION['error'] = "Please select products";
//         header("Location: ./");
//         exit;
//     }
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Simple CRUD App (PHP OOP)</title>
  <link rel="stylesheet" href="./css/globals.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/product-list.css">
  <script src="./scripts/script.js"></script>
</head>

<body>
  
  <!-- Header -->
  <header class="header">
    <div class="header-inner">
      <div class="header-main header-left"><h1>Product List</h1></div>
      <div class="header-main header-right">
        <a href="add-product"><button class="button" id="add-product-btn" name="ADD">ADD</button></a>
        <button class="button" id="edit-product-btn" disabled onclick="submitAction('formEdit')">EDIT</button>
        <button class="button" id="delete-product-btn" disabled onclick="submitAction('formDelete')">DELETE</button>
      </div>
    </div>
  </header>

  <!-- Main -->
  <div class="main-container">
  <main class="main-content">

    <!-- Message box -->
    <div class="message">
      <?php flashMessages(); ?> 
    </div>


    <!-- NO PRODUCTS VIEW -->
    <?php
    if (!$productList) {
        include __DIR__ . '/shared/no-product.php';
        exit;
    }
    ?>

    <!-- SORT DROPDOWN -->
    <div class="header-sort">
        <form method="get" id="formSort"> 
            <select id="selectSort" name="sort" onchange="formSubmit('formSort')">
                <option name="sort_new" value="new">New products first</option>
                <option name="sort_old" value="old">Old products first</option>
                <option name="sort_priceUp" value="price_up">Price (cheaper first)</option>
                <option name="sort_priceDown" value="price_down">Price (expensive first)</option>
            </select>
          </form>
      </div>  
  
    <!-- PRODUCT LIST -->
    <form method="post" id="formPost">
      <div class="product-container">
      <?php
        foreach ($productList as $product) {
            echo '<div class="product-item">' . $product->preview() . '</div>';
        }
        ?>
      </div>
    </form>
    
    <!-- HIDDEN ACTION FORMS -->
    <form method="post" action="./actions/edit.php" id="formEdit">
      <input type="hidden" name="action" value="edit">
    </form>

    <form method="post" action="./actions/delete.php" id="formDelete">
      <input type="hidden" name="action" value="delete">
    </form>

    </main>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . '/shared/footer.php'; ?>

    <script>
      formSortSelect();
    </script>
  
</body>
</html>