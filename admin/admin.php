<?php
/**
 *
 * Страница каталога для админа.
 * 
 */
session_start();
// Проверка, есть ли у пользователя права администратора. Авторизация.
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
	echo "<a href='../catalog.php'>Return to catalog </a><br>";
	exit("You don't have admin rights <br>");
}
require('./link.php');
require('../Functions/adminFunctions.php');
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
					<a class='catalog__link' href='./product-edit.php?productId=$productId' >
						<div class='catalog__wrapper'>
							<img class='catalog__image' id='$productId' src='$productUrl' alt='product-$productId'>
						</div>
						<p class='catalog__title'><b>$title</b></p>
						<p class='catalog__price'>Price: $price &#8381;</p>
					</a>
					<div class='catalog__admin'>
						<a class='catalog__edit' href='./product-edit.php?productId=$productId'><i class='fas fa-edit '></i></a>
						<a class='catalog__del' href='./delete.php?productId=$productId'><i class='fas fa-trash-alt'></i></a>
					</div>
				</div>";
		}
	}
	mysqli_close($link);
	return $render;
}
?>

<!DOCTYPE html>
<html>
<head>
	<title >Gallery</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../styles.css">
	<script src="https://kit.fontawesome.com/a03bfa2223.js" crossorigin="anonymous"></script>
</head>
<body>
	<h2 class="heading">Catalog</h2>
	<nav class="nav-center">
		<a class="nav-link m0" href="product-add.php"><i class="fas fa-plus"></i> Add new product</a>
		<a class="nav-link m0" href="orders.php">Orders</a>
	</nav>
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

