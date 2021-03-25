<?php
  require('link.php');
  session_start();
  if (!isset($_SESSION['cart'])) {
    header("Location: cart.php");
  }
  global $link;
  // Загружаем товары из БД, которые находятся в корзине.
  $iDs = implode(', ', array_keys($_SESSION['cart']));
  $select = "SELECT * FROM products WHERE id IN ($iDs) ORDER BY counter_clicks DESC";
  $cartResource = mysqli_query($link, $select);
  if (!empty($_POST)) {
    $address = mysqli_escape_string($link, htmlspecialchars(strip_tags($_POST['address'])));
    $phone = (string) mysqli_escape_string($link, htmlspecialchars(strip_tags($_POST['phone'])));
    /**
     * Переменная необохдима для дальнейшего использования при записи продуктов из заказа в БД.
     * @var array
     */
    $cartArray = [];
    if ($cartResource) {
      while ($product = mysqli_fetch_assoc($cartResource)) {
        $productId = $product['id'];
        $price = $product['price'];
        $qty = $_SESSION['cart'][$productId];
        $cartArray[] = [$productId, $qty, $price];
      }
      $userId = $_SESSION['userId'];
      $date = date("Y-m-d H:i:s");
      // Создаем новый заказ в БД.
      $insertOrder = "INSERT INTO orders (user_id, address, phone, date, status)
        VALUES ('$userId', '$address', '$phone', '$date', 'new')";
      if (!mysqli_query($link, $insertOrder)) {
        echo 'Не удалось создать новый заказ в БД';
      }
      // Созданный ID для нового заказа.
      $newOrderId = mysqli_insert_id($link);
      // Переменная содержащая VALUES для запроса в БД.
      $insertCartValues = '';
      foreach ($cartArray as $product) {
        $strProduct = implode(', ', $product);
        $insertCartValues .= "ROW ($newOrderId, {$strProduct}), ";
      }
      $insertCartValues = substr($insertCartValues, 0, -2);
      $insertCart = "INSERT INTO ordered_items (order_id, product_id, qty, price)
        VALUES {$insertCartValues}";
      echo "<br>";
      if (!mysqli_query($link, $insertCart)) {
        echo 'Не удалось записать заказанные товары в БД.';
      }
      unset($_SESSION['cart']);
    } else {
      echo "Cart is Empty";
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title >Cart</title>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <script src="https://kit.fontawesome.com/a03bfa2223.js" crossorigin="anonymous"></script>
</head>
<body>
  <h2 class="heading">Checkout</h2>
  <nav class="nav-center">
    <a class="nav-link m0" href="cart.php">Cart</a>
    <a class="nav-link m0" href="catalog.php">Catalog</a>
  </nav>
  <section class="checkout-section">
    <div class="checkout-items">
      <?php 
        $num = 1;
        $total = 0;
        if ($cartResource) {
          while ($product = mysqli_fetch_assoc($cartResource)) {
            $productId = $product['id'];
            $price = $product['price'];
            $title = $product['title'];
            $qty = $_SESSION['cart'][$productId];
            $sum = $qty * $price;
            echo "<p> {$num}. $title - $qty pcs. - $sum rub.</p>";
            $num++;
            $total += $sum;
          }
          echo "<p><b>Total: $total rub. </b></p>";
        }
      ?>    
    </div>
    <div class="checkout">
      <form class="order" name="checkout" method="post">
        <label for="name">Your name: </label><input type="text" name="name" id="name" 
        required value=<?= $_SESSION['name']?>>
        <label for="address">Address: </label><input type="text" name="address" id="address"
        required>
        <label for="phone">Telephone: </label><input type="tel" name="phone" id="phone" required>
        <input class="buy-btn" type="submit" value="Order"></input>
      </form>
    </div>
  </section>
</body>
</html>
<?php mysqli_close($link) ?>