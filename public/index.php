<?php 
session_start();
require_once __DIR__ . '/../config/bootstrap.php';
use App\Models\Product;

$productList = getSortedProductList();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (!empty($_POST['product'])) {
    $deletedCount = 0;

    foreach ($_POST['product'] as $product_id) {
      $product = Product::find_by_id('product_id', $product_id);
      if ($product) {
          $product->delete();
          $deletedCount++;
      }
    }

    $_SESSION['success'] = $deletedCount . " product(s) deleted.";
    header("Location: ./");
    exit;
  } else {
    $_SESSION['error'] = "Please select products";
    header("Location: ./");
    exit;
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<!-- HTML HEAD -->
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Simple CRUD App (PHP OOP)</title>
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/index.css">
  <script src="./scripts/script.js"></script>
</head>
<!-- end -->

<!-- HTML BODY -->
<body>
<!-- Header -->
<div class="header">
  <div class="header-main header-left"><h1>Product List</h1></div>
  <div class="header-main header-right">
    <a href='add-product'><button class="button" id="add-product-btn" name="ADD">ADD</button></a>
    <input type="submit" class="button" id="delete-product-btn" value="DELETE" name="DELETE" onclick="formSubmit('formPost')"/>
  </div>
</div>
<!-- end -->

<div class="wrapper">
<!-- Message box -->
 <div class="message">
  <?php flashMessages(); ?> 
 </div>
<!-- end -->

<?php
    if(!$productList) {
      echo '<div id="no-product">';
      echo '<img id="no-product-img" src="./images/no-product-found.png">';
      echo '<p>NO PRODUCT</p><p>FOUND</p>';
      echo '</div>';
      echo '<div class="push"></div> </div>';
      echo '<footer class="footer">';
      echo '<div class="footer-text">Simple CRUD App (PHP OOP)</div>';
      echo '</footer>';      
      exit;
    } 
?>
<div class="header-sort">
    <form method="get" id="formSort"> 
        <!-- <label for="selectSort">Sort by:</label> -->
        <select id="selectSort" name="sort" onchange="formSubmit('formSort')">
            <option name="sort_new" value="new">New products first</option>
            <option name="sort_old" value="old">Old products first</option>
            <option name="sort_priceUp" value="price_up">Price (cheaper first)</option>
            <option name="sort_priceDown" value="price_down">Price (expensive first)</option>
        </select>
      </form>
  </div>  
<!-- Main Content -->
<form method="post" id="formPost">
<div class="product-container">
<?php
        foreach ($productList as $product) {
        echo '<div class="product-item">'.$product->preview().'</div>';
        }
  ?>
</div>
</form>
<!-- end -->

<!-- Footer -->
<div class="push"></div>
</div>
<footer class="footer">
    <div class="footer-text">Simple CRUD App (PHP OOP)</div>
</footer>
<script>
  formSortSelect();
</script>
<!-- end -->
</body>
<!-- HTML END -->
</html>