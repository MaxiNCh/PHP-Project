<?php 

require('../link.php');

session_start();

$productId = (int) $_GET['productId'];
// Проверяем был ли в корзину уже данный товар. Если был увеличиваем количество на 1.
if (isset($_SESSION['cart'][$productId])) {
	unset($_SESSION['cart'][$productId]);
}

header("Location: ../product.php?productId=$productId");

?>