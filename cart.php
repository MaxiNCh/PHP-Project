<?php

require('link.php');

session_start();


/**
 * Функция подключается к базе данных картинок, по этим данным возвращаем блок с кртинками.
 * @param  [string] $dir [адрес папки, где хранятся картинки]
 * @return [string]      [возращает HTML блок с картинками]
 */
function renderProduct($dir)
{
	global $link;

	if (!isset($_SESSION['cart'])) {
		return [null, 0];
	}

	// Создаем строку $iDs, в которой содержатся id продуктов, которые содержатся в корзине.
	$iDs = implode(', ', array_keys($_SESSION['cart']));

	$render = '';
	$total = 0;
	
	if ($result = mysqli_query($link, "SELECT * FROM products WHERE id IN ($iDs) ORDER BY counter_clicks DESC")) {
		while ($product = mysqli_fetch_assoc($result)) {
			$productName = $product['name'];
			$productUrl = $dir . $productName;
			$productId = $product['id'];
			$qty = $_SESSION['cart'][$productId];
			$title = $product['title'];
			$price = $product['price'];
			$subTotal = $price * $qty;
			$total += $subTotal;
			$render .= 
				"<div class='catalog__product'>
					<a class='catalog__link' href='counter.php?productId=$productId' >
						<div class='catalog__wrapper'>
							<img class='catalog__image' id='$productId' src='$productUrl'
							alt='product-$productId'>
						</div>
						<p class='catalog__title'><b>$title</b></p>
						<p class='catalog__price'>Quantity: $qty</p>
						<p class='catalog__price'>Subtotal: $subTotal &#8381;</p>
					</a>
				</div>";
		}
	}
	mysqli_close($link);
	// Возвращаем массив, содержащий:
	// [0] - HTML часть
	// [1] - сумму всех товаров в корзине.
	return [$render, $total];
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
	<h2 class="heading">Cart</h2>
	<a class="catalog__cart-link" href="catalog.php"><h3 class="catalog__h3">Catalog</h3></a>
	<section class="section">
		<div class="products">
			<?php
				$products = renderProduct($DIR);
				echo $products[0];
			?>
		</div>
		<?php if ($products[1] != 0) { ?>
			<h3 class='cart__total'>Total price: <?= $products[1] ?></h3>
			<a href="checkout.php" class="btn buy-btn">
				<i class="fas fa-money-check-alt"></i><span> Купить</span>
			</a>
		<?php } else { ?>
			<h3 class='cart__total'>Cart is empty</h3>
		<?php }	?>
	</section>
<script>
</script>
</body>
</html>
