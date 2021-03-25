<?php 
	
	/**
	 * Данная страница выполняет икремент счетчика при клике на картинку.
	 * Этот функционал выделен в отдельную страницу, чтобы при нажатии 'F5' на странице 
	 * с картинкой счетик автоматически не прибавлялся.
	 */

	require('link.php');

	$productId = (int) $_GET['productId'];

	global $link;

	$update = "UPDATE products SET counter_clicks = counter_clicks + 1 WHERE id = '$productId'";

	if (!($result = mysqli_query($link, $update))) {
		echo 'UPDATE ERROR';
	}

	mysqli_close($link);

	header("Location: product.php?productId=$productId");

?>