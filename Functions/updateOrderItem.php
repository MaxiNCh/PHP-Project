<?php 
require('../admin/link.php');
session_start();
global $link;
$qty = mysqli_escape_string($link, htmlspecialchars(strip_tags($_POST['qty'])));
$price = mysqli_escape_string($link, htmlspecialchars(strip_tags($_POST['price'])));
if (is_numeric($qty) && is_numeric($price)) {
	$update = "UPDATE ordered_items SET qty=$qty, price=$price WHERE order_id = {$_POST['orderId']} AND product_id = {$_POST['productId']}";
	mysqli_query($link, $update);
	mysqli_close($link);
	$_SESSION['msg'][] = "Order {$_POST['orderId']} has been updated";
	header("Location: ../admin/orders.php");
} else {
	$_SESSION['error'][] = 'Not numberic values passed';
	header("Location: ../admin/orders.php");
}
?>