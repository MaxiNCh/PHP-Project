<?php 

session_start();
require('link.php');


/**
 * Функция проверяет есть ли уже в базе данных пользователь с таким логином. 
 * Если уже есть, то выводит комментарий.
 * Если такого пользователя нет, то добавляет нового пользователя в БД.
 * @return void
 */
function registration()
{
	echo "<br>";
	if (isset($_POST['userLogin'])) {

		global $link;

		$userLogin = mysqli_escape_string($link, (string) htmlspecialchars(strip_tags($_POST['userLogin'])));

		$select = "SELECT * FROM users WHERE login = '$userLogin'";

		$result = mysqli_query($link, $select);

		$user = mysqli_fetch_assoc($result);

		if (isset($user)) {
			echo "<h5> Such login already exists </h5>";
		} else {
			$userName = mysqli_escape_string($link, (string) htmlspecialchars(strip_tags($_POST['userName'])));
			$password = mysqli_escape_string($link, (string) htmlspecialchars(strip_tags($_POST['password'])));
			$pasHash = password_hash($password, PASSWORD_BCRYPT);
			$isAdmin = isset($_POST['isAdmin']) ? 1 : 0;
			$insert = "INSERT INTO users (name, login, password, admin) VALUES ('$userName', '$userLogin', '$pasHash', '$isAdmin')";
			mysqli_query($link, $insert);
			mysqli_close($link);
			echo "<h5> User has been added </h5>";

		}

	}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registration</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="styles.css">
	<script src="https://kit.fontawesome.com/a03bfa2223.js" crossorigin="anonymous"></script>
</head>
<body>

	<header>
		<h2 class="heading">Registration</h2>	
	</header>
	<nav class="nav"><a class="nav-link" href="catalog.php">Catalog</a></nav>

	<div class="registration">
		<form method="post">
			<label for="userName">Your name: </label>
			<input id="userName" name="userName" type="text" required>
			<br>
			<label for="userLogin">Your Login: </label>
			<input type="text" name="userLogin" id="userLogin" required>
			<br>
			<label for="password">Password: </label>
			<input type="password" name="password" id="password" required>
			<br>
			<!-- В учебных целях добавил возможность самостоятельно выбирать при регистрации, есть ли права админа -->
			<label for="isAdmin">Admin</label>
			<input id="isAdmin" name="isAdmin" type="checkbox">
			<br>
			<input class="submit-btn submit-border" type="submit" name="submit" value="Registration">
			<?php registration() ?>
		</form>

	</div>
	

</body>
</html>