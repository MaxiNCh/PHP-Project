<?php 

require('link.php');

$productId = (int) $_GET['productId'];

session_start();

/**
 * Функция подключается к базе данных, возвращает картинку, инкрементирует количество посещений
 * @param  [string] $id  [ID картинки]
 * @param  [string] $dir [Адрес папки с картинками]
 * @return [string]      [Возвращает блок HTML с картинкой]
 */
function renderImage($id, $dir)
{
	global $link;

	$select = "SELECT * FROM products WHERE id = '$id'";
	if ($result = mysqli_query($link, $select)) {
		while ($product = mysqli_fetch_assoc($result)) {
			$name =$product['name'];
			$url = $dir . $name;
			$counter = $product['counter_clicks'];
			$title = $product['title'];
			$price = $product['price'];
			$render = 
			"
				<img class='product-img' src='$url' alt='product-$id'>
				<div class='product-body'>
					<p class='product-text'><b> $title </b></p>
					<p class='product-text'> Price: $price &#8381;</p>
					<p class='product-text'> Популярность: $counter </p>
			";
		}

		if (isset($_SESSION['cart'][$id])) {
		$qty = $_SESSION['cart'][$id];
		$subTotal = $qty * $price;
		$render .= 
			"<p class='product-text'> Количество товара в корзине: $qty шт. </p> 
			<p class='product-text'> Общая стоимость: $subTotal &#8381;</p> ";
		}

		$render .= "</div>";
	}



	mysqli_close($link);

	return $render;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Product</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="styles.css">
	<script src="https://kit.fontawesome.com/a03bfa2223.js" crossorigin="anonymous"></script>

</head>
<body>
	<header>
		<h2 class="heading">Product</h2>	
	</header>
	<nav class="nav-center">
		<a class="nav-link m0" href="cart.php">Cart</a>
		<a class="nav-link m0" href="catalog.php">Catalog</a>
	</nav>

	<section class="product">
			<?php 
				echo renderImage($productId, $DIR);
			?>
			<div class="cart__links">
				<a class='add-to-cart-link' href="./Functions/addToCart.php?productId=<?= $productId ?>">
					<i class='fas fa-cart-plus'></i> Add to cart
				</a>
				<a class='del-from-cart-link' 
					href="./Functions/delFromCart.php?productId=<?= $productId ?>">
					<i class="fas fa-trash"></i> Delete from cart
				</a>
			</div>
		
	</section>
	
</body>
</html>