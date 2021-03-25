<?php 

require('../link.php');

session_start();

$productId = (int) $_GET['productId'];
// Проверяем был ли в корзину уже данный товар. Если был увеличиваем количество на 1.
if (isset($_SESSION['cart'][$productId])) {
	$_SESSION['cart'][$productId] += 1;
} else {
	$_SESSION['cart'][$productId] = 1;
}

header("Location: ../product.php?productId=$productId");

?>