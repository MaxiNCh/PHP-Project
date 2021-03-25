<?php

require('link.php');
require('./Functions/SignIn.php');

session_start();

/**
 * Функция подключается к базе данных картинок, по этим данным возвращаем блок с кртинками.
 * @param  [string] $dir [адрес папки, где хранятся картинки]
 * @return [string]      [возращает HTML блок с картинками]
 */
function renderImages($dir)
{
	global $link;
	$render = '';
	if ($result = mysqli_query($link, 'SELECT * FROM products ORDER BY counter_clicks DESC')) {
		while ($product = mysqli_fetch_assoc($result)) {
			$productName = $product['name'];
			$productUrl = $dir . $productName;
			$productId = $product['id'];
			$productCounter = $product['counter_clicks'];
			$title = $product['title'];
			$price = $product['price'];
			$render .= 
				"<div class='catalog__product'>
					<a class='catalog__link' href='counter.php?productId=$productId' >
						<div class='catalog__wrapper'>
							<img class='catalog__image' id='$productId' src='$productUrl' alt='product-$productId'>
						</div>
						<p class='catalog__title'><b>$title</b></p>
						<p class='catalog__price'>Price: $price &#8381;</p>
					</a>
					<a class='add-to-cart-link' href='Functions/addToCart.php?productId=$productId'>
						<i class='fas fa-cart-plus'></i> Add to cart
					</a>
				</div>";
		}
	}
	mysqli_close($link);
	return $render;
}
signIn();
?>

<!DOCTYPE html>
<html>
<head>
	<title >Gallery</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="styles.css">
	<script src="https://kit.fontawesome.com/a03bfa2223.js" crossorigin="anonymous"></script>

</head>
<body>
	<header class="header">
		<?php 
			if (isset($_SESSION['login'])) {
				echo "<div class='greeting'>";
				// Если у пользователя есть права администратора, то добавляется ссылка на страницу редактирования.
				if ($_SESSION['admin']) {
					echo "<a class='sign-in' href='admin/admin.php'> Admin </a>";
				}
				echo "<h4 > Hello {$_SESSION['name']}! </h4>
						<a class='sign-in' href='signOut.php'>Sign Out</a>
					</div>
				";
			} else {
				echo "
				<div class='sign-in__links'>
					<form method='POST'>
						<label for='userLogin	'>Login: </label>
						<input type='text' id='userLogin' name='userLogin' required>
						<label for='password'>Password: </label>
						<input type='password' id='password' name='password' required>
						<input class='submit-btn' type='submit' value='Sign in'>";
				echo "</form>
					<a class='sign-in' href='registration.php'>Registration</a>
				</div>
				";
			}
		?>
		
	</header>
	<h2 class="heading">Catalog</h2>
	<a class="catalog__cart-link" href="cart.php"><h3 class="catalog__h3"> Cart</h3></a>
	<section class="section">
		<div class="products">
			<?php
				echo renderImages($DIR);
			?>
		</div>
	</section>
	<script>
	</script>
</body>
</html>

