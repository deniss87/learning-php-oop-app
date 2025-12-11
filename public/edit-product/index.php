<?php
session_start();
require_once(__DIR__ . '/../../config/bootstrap.php');
use App\Services\Validation;
use App\Models\Category;
use App\Models\Product;

$id = isset($_GET['id']) ?  intval($_GET['id']) : 0;

if ($id <= 0) {
    $_SESSION['error'] = "Invalid product ID";
    header("Location: ../");
    exit;
}

$product = Product::getProductById($id, true);

if (!$product) {
    $_SESSION['error'] = "Product not found";
    header("Location: ../");
    exit;
}

// Prepare default args from model
$args = [
    'product_sku'     => $product->product_sku,
    'product_name'    => $product->product_name,
    'product_price'   => $product->product_price,
    'category_id'     => $product->category_id,
    'category_name'     => $product->category_name,

    'product_size'    => $product->product_size ?? '',
    'product_weight'  => $product->product_weight ?? '',
    'product_height'  => $product->product_height ?? '',
    'product_width'   => $product->product_width ?? '',
    'product_length'  => $product->product_length ?? '',
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = new Validation($_POST['product'], $product->product_id);

    if ($data->validate()) {
        $categoryName = Category::getCategoryName($_POST['product']['category_id']);
        $className = "\\App\\Models\\" . $categoryName;
        $product = new $className((array)$product);

        // Fill updated data into model
        $product->product_sku   = $_POST['product']['product_sku'];
        $product->product_name  = $_POST['product']['product_name'];
        $product->product_price = $_POST['product']['product_price'];
        $product->category_id   = $_POST['product']['category_id'];

        $product->product_size = $_POST['product']['product_size'] ?? null;
        $product->product_weight = $_POST['product']['product_weight'] ?? null;
        $product->product_height = $_POST['product']['product_height'] ?? null;
        $product->product_width  = $_POST['product']['product_width'] ?? null;
        $product->product_length = $_POST['product']['product_length'] ?? null;

        // Update a record in the database
        $product->save();

        $_SESSION['success'] = "Product updated successfully.";
        header("Location: ../");
        exit;
    }

    $args = $_POST['product'];
}

$categoryList = Category::getAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Product</title>
  <link rel="stylesheet" href="../css/globals.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/product-add.css">
  <script src="../scripts/script.js"></script>
</head>

<body>

  <!-- Header -->
  <header class="header">
    <div class="header-inner">
      <div class="header-main header-left"><h1>Edit Product</h1></div>
      <div class="header-main header-right">
        <input type="submit" class="button" value="Save" onclick="formVerify()"/>
        <a href='../'><button class="button">Cancel</button></a>
      </div>
    </div>
  </header>

  <div class="main-container">
  <main class="main-content">

    <!-- Flash Messages -->
    <div class="message">
        <div class="message-text"><?php flashMessages(); ?></div>
    </div>

    <!-- Form -->
    <div class="form-container">
      <form method="post" id="product_form">

      <div class="grid-item">
          <label for="sku">SKU:</label>
          <input 
            type="text"
            id="sku" 
            name="product[product_sku]" 
            inputname="SKU"
            value="<?= e($args['product_sku']) ?>" 
            required
          >
      </div>

      <div class="grid-item">
          <label for="name">Name:</label>
          <input 
            type="text" 
            id="name" 
            name="product[product_name]" 
            inputname="Name"
            value="<?= e($args['product_name']) ?>" 
            required
          >
      </div>

      <div class="grid-item">
          <label for="price">Price ($):</label>
          <input 
            type="number"
            id="price" 
            name="product[product_price]" 
            inputname="Price"
            onkeypress="return !['e','E','-','+'].includes(event.key)"
            value="<?= e($args['product_price']) ?>" 
            required
          >
      </div>

      <div class="grid-item">
          <label for="productType">Product category:</label>
          <select id="productType" name="product[category_id]" inputname="Product category"
                  onChange="showProductSpecs()" required>

              <option value="">please select</option>

              <?php foreach ($categoryList as $category) : ?>
                  <option value="<?= e($category['category_id']) ?>"
                      <?= $args['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                      <?= e($category['category_name']) ?>
                  </option>
              <?php endforeach; ?>

          </select>
      </div>

      <div id="DVD">
        <p class="product-type-desc">Please, provide product size in MB</p>
        <label for="size">Size (MB):</label>
        <input 
          type="number" 
          id="size" 
          name="product[product_size]"
          onkeypress="return !['e','E','-','+'].includes(event.key)"
          value="<?= e($args['product_size']) ?>"
        >
      </div>

      <div id="Book">
        <p class="product-type-desc">Please, provide weight in KG</p>
        <label for="weight">Weight (KG):</label>
        <input 
          type="number"
          id="weight"
          name="product[product_weight]"
          onkeypress="return !['e','E','-','+'].includes(event.key)"
          value="<?= e($args['product_weight']) ?>"
        >
      </div>

      <div id="Furniture">
        <p class="product-type-desc">Please, provide dimensions in H x W x L format</p>

        <label for="height">Height (cm):</label>
        <input 
          type="number"
          id="height" 
          name="product[product_height]"
          onkeypress="return !['e','E','-','+'].includes(event.key)"
          value="<?= e($args['product_height']) ?>"
        >

        <label for="width">Width (cm):</label>
        <input 
          type="number" 
          id="width" 
          name="product[product_width]"
          onkeypress="return !['e','E','-','+'].includes(event.key)"
          value="<?= e($args['product_width']) ?>"
        >

        <label for="length">Length (cm):</label>
        <input 
          type="number"
          id="length" 
          name="product[product_length]"
          onkeypress="return !['e','E','-','+'].includes(event.key)"
          value="<?= e($args['product_length']) ?>"
        >
      </div>

      </form>
    </div>

  </main>
  </div>

  <?php include __DIR__ . '/../shared/footer.php'; ?>

  <script>showProductSpecs();</script>
</body>
</html>