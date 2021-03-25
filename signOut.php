<?php 
session_start();
unset($_SESSION['cart']);
unset($_SESSION['name']);
unset($_SESSION['login']);
unset($_SESSION['admin']);
session_destroy();
header("Location: catalog.php");
 ?>