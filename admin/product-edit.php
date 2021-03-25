<?php 

/**
 *
 * Страница продукта с возможностью редактирования параметров продукта
 * 
 */

require('./link.php');
require('../Functions/adminFunctions.php');

session_start();
// Проверка, есть ли у пользователя права администратора.
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
	echo "<a href='../catalog.php'>Return to catalog </a><br>";
	exit("You don't have admin rights <br>");
}

$productId = (int) $_GET['productId'];

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
				"<div class='product-edit__body'>
					<img class='product-img' src='$url' alt='product-$id'>
					<p class='product-text'><b> $title </b></p>
					<p class='product-text'> Price: $price &#8381;</p>
					<p class='product-text'> Популярность: $counter </p>
				</div>";
		}
	}
	// mysqli_close($link);

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
	<link rel="stylesheet" href="../styles.css">
	<script src="https://kit.fontawesome.com/a03bfa2223.js" crossorigin="anonymous"></script>

</head>
<body>
	<header>
		<h2 class="heading">Product</h2>	
	</header>
	<nav class="nav"><a class="nav-link" href="admin.php">Gallery</a></nav>

	<section class="product-edit">
			<?php 
				echo renderImage($productId, $DIR);
			?>
			<div class="product-edit__form">
				<div class="upload">
					<form enctype="multipart/form-data" method="post">
						<!-- Ограничивает максимальный размер файл 1Мб -->
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
						<input type="file" id="avatar" name="avatar" accept=".jpg,.png">
						<br>
						<label for="title">Наименование</label>
						<input type="text" id="title" name="title">
						<br>
						<label for="price">Price</label>
						<input type="text" id="price" name="price">
						<br>
						<input value="Обновить" type="submit">
					</form>
				</div>
				<div class="catalog__delete">
					<a href="./delete.php?productId=<?= $productId ?>"><i class='fas fa-trash-alt catalog__del-big'></i></a>
				</div>
			</div>
			<?php
				updateProduct($productId, $DIR);
			?>
		
	</section>
	
</body>
</html>