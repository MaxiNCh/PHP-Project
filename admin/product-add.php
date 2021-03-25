<?php 

/**
 *
 * Страница для добавления нового продукта
 * 
 */
require('../Functions/adminFunctions.php');
require('./link.php');
session_start();

// Проверка, есть ли у пользователя права администратора.
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
	echo "<a href='../catalog.php'>Return to catalog </a><br>";
	exit("You don't have admin rights <br>");
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
		<h2 class="heading">Add new product</h2>	
	</header>
	<nav class="nav"><a class="nav-link" href="admin.php">Catalog</a></nav>

	<section class="product-add">
		<div class="product-add__form">
			<form enctype="multipart/form-data" method="post">
				<!-- Ограничивает максимальный размер файл 1Мб -->
				<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
				<input type="file" id="avatar" name="avatar" accept=".jpg,.png">
				<br>
				<label for="title">Наименование</label>
				<input type="text" id="title" name="title">
				<br>
				<label for="price">Цена</label>
				<input type="text" id="price" name="price">
				<br>
				<input value="Добавить" type="submit">
			</form>
		</div>
		<?php 
			addProduct($DIR);
		?>
	</section>
	
</body>
</html>