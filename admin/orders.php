<?php
/**
 * Страница заказов, для просмотра админу.
 */
session_start();
// Проверка, есть ли у пользователя права администратора. Авторизация.
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
	echo "<a href='../catalog.php'>Return to catalog </a><br>";
	exit("You don't have admin rights <br>");
}
require('./link.php');
global $link;
$select = "SELECT ordered_items.qty, ordered_items.price, users.name, orders.id AS orderId, products.title, products.id FROM orders INNER JOIN (ordered_items, users, products) ON (orders.id = ordered_items.order_id AND orders.user_id = users.id AND ordered_items.product_id = products.id)";
	$productsInOrders = mysqli_query($link, $select);
?>

<!DOCTYPE html>
<html>
<head>
	<title >Orders</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../styles.css">
	<script src="https://kit.fontawesome.com/a03bfa2223.js" crossorigin="anonymous"></script>
</head>
<body>
	
	<h2 class="heading">Orders</h2>
	<nav class="nav-center">
		<a class="nav-link m0" href="admin.php">Catalog</a>
	</nav>
	<section class="orders">
			<table class="orders__table">
				<caption>Orders table</caption>
				<colgroup>
					<col style="width: 4%">
					<col style="width: 7%" span="3">
					<col style="width: 10%" span="2">
					<col style="width: 5%">
				</colgroup>
				<tr>
					<th>#</th>
					<th>Order id</th>
					<th>User</th>
					<th>Product</th>
					<th>Quantity</th>
					<th>Price</th>
					<th>Update</th>
				</tr>
				<?php 
					$i = 1;
					while ($product = mysqli_fetch_assoc($productsInOrders)) {
						// Выводим в таблице заказанные товары. Поля 'Quantity', 'Price' можно
						// редактировать.
						echo 
							"<tr>
								<form action='../Functions/updateOrderItem.php' method='post'>
								<input name='orderId' value='{$product['orderId']}' hidden>
								<input name='productId' value='{$product['id']}' hidden>
 								<td> {$i} </td>
								<td> {$product['orderId']} </td>
								<td> {$product['name']} </td>
								<td> {$product['title']} </td>
								<td>
									<input name='qty' type='text' value={$product['qty']}>
								</td>
								<td>
									<input name='price' type='text' value={$product['price']}>
								</td>
								<td>
									<input type='submit' class='update-btn' value='OK'>
								</td>
								</form>
							</tr>";
						$i++;
					}
				?>
			</table>
			<br>
			<?php 
				if (isset($_SESSION['error'])) {
					$error = $_SESSION['error'];
					foreach ($error as $errorText) {
						echo "<p style='color: red'> $errorText </p>";
					}
					unset($_SESSION['error']);
				}
				if (isset($_SESSION['msg'])) {
					$msg = $_SESSION['msg'];
					foreach ($msg as $msgText) {
						echo "<p> $msgText </p>";
					}
					unset($_SESSION['msg']);
				}
			?>
	</section>
	<script>
	</script>
</body>
</html>

