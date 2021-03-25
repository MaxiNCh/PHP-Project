<?php 

require('./link.php');

function addProduct($dir)
{
	global $link;

	// Проверяем, передано ли что-то в посто.
	if (!empty($_POST)) {

		$imgName = uploadImg($dir);
		$productTitle = mysqli_escape_string($link, htmlspecialchars(strip_tags($_POST['title'])));
		$productPrice = (float) ($_POST['price']);

		// Проверка, были ли введены данные в форму.
		// 
		if (!empty($imgName) && !empty($productTitle) && !empty($productPrice)) {
			$insert = "INSERT INTO products (title, price, name, counter_clicks)
				VALUES ('$productTitle', '$productPrice', '$imgName', 0)";

				echo "$insert <br>$imgName $productTitle $productPrice";


			if (mysqli_query($link, $insert)) {
				$select = "SELECT id FROM products ORDER BY id DESC LIMIT 1";
				if ($result = mysqli_query($link, $select)) {
					$newProduct = mysqli_fetch_assoc($result);
					$newProductId = $newProduct['id'];					
					header("Location: product-edit.php?productId=$newProductId");
				} else {
					echo "Считывания нового продукта";
				}
				
			} else {
				echo 'Ошибка записи';
			}

		} else {
			echo 'Ошибка данных';
		}
	}
	
}

/**
 * Функция обновляет параметры продукат
 * @param  [string] $id  ID-продукта
 * @param  [string] $dir директория хранения картинки
 * @return [void]      
 */
function updateProduct($id, $dir)
{
	global $link;

	// Проверяем, передано ли что-то в посто.
	if (!empty($_POST)) {

		$imgName = uploadImg($dir);
		$productTitle = mysqli_escape_string($link, htmlspecialchars(strip_tags($_POST['title'])));
		$productPrice = (float) ($_POST['price']);


		// Проверка, были ли введены данные в форму.
		// 
		if (!empty($imgName) && !empty($productTitle) && !empty($productPrice)) {
			$update = "UPDATE products SET 
				title = '$productTitle',
				price = '$productPrice',
				name = '$imgName'
				WHERE id = '$id'";

				echo "$update <br>$imgName $productTitle $productPrice id: $id";

			if (mysqli_query($link, $update)) {
				header("Location: product-edit.php?productId=$id");
			} else {
				echo 'Ошибка записи';
			}

		} else {
			echo 'Ошибка данных';
		}
	}
}

/**
 * Функция копирует в папку новую картинку и добавляет информацию о ней в базу данных
 * @param  [string] $dir [адрес папки с картинками]
 * @return [void]      [ничего не возвращает]
 */
function uploadImg($dir)
{
	if (isset($_FILES['avatar'])) {
		$newImg = $_FILES['avatar'];
		// Проверка на ошибки и на правильный тип файла (.png или .jpg)
		if (($newImg['error'] == UPLOAD_ERR_OK) &&
			($newImg['type'] == 'image/png' || $newImg['type'] == 'image/jpeg')) 
		{
			$imgName = basename($newImg['name']);
			if (file_exists("$dir/$imgName")) {
				echo "<br> Файл уже существует <br>";
				return $imgName;
			} else {
				move_uploaded_file($newImg['tmp_name'], "$dir/$imgName");
				return $imgName;
			}
			
		} else {
			echo "<br>Неверный формат файла.";
		}
	}
}




?>