<?php 

/**
 * Функция осуществляет аутентификацию пользователя. Проверяет наличие такого логина в БД.
 * Есть такой пользователь есть, то проверяется пароль.
 * @return [type] [description]
 */
function signIn()
	{	
		echo "<br>";
		if (isset($_POST['userLogin'])) {
			global $link;

			$userLogin = mysqli_escape_string($link, (string) htmlspecialchars(strip_tags($_POST['userLogin'])));
			$password = mysqli_escape_string($link, (string) htmlspecialchars(strip_tags($_POST['password'])));


			$select = "SELECT * FROM users WHERE login = '$userLogin'";
			$result = mysqli_query($link, $select);
			$user = mysqli_fetch_assoc($result);
			if (!$user) {
				echo "<h5 style='color: red'>Login or password incorrect</h5>";
			} else {
				if (password_verify($password, $user['password'])) {
					$_SESSION['name'] = $user['name'];
					$_SESSION['login'] = $user['login'];
					$_SESSION['userId'] = $user['id'];
					$_SESSION['admin'] = ($user['admin'] == 1) ? true : false ;
					header("Loacation: catalog.php");
				} else {
					echo "<h5 style='color: red'>Login or password incorrect</h5>";
				}
			}

		} 
	}
	?>