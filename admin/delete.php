<?php 
/**
 *
 *	Файл содержит процедуру удаления продукта
 * 
 */
require('./link.php');
session_start();

// Проверка, есть ли у пользователя права администратора.
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
	echo "<a href='../catalog.php'>Return to catalog </a><br>";
	exit("You don't have admin rights <br>");
}

global $link;

$id = (int) (int) $_GET['productId'];

$delete = "DELETE FROM products WHERE id = $id";

if (mysqli_query($link, $delete)) {
	mysqli_close($link);
	header("Location: admin.php");
} else {
	echo "$id";
	echo 'Ошибка удаления';
}


?>