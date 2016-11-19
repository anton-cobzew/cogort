<?php
	session_start();
	if (isset($_SESSION['authorized']))
		header('Location: friends.php');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<?php include('include/html_default_head.php'); ?>
	<title>Вход в систему | Cogort</title>
	<link rel="stylesheet" href="css/login.css">
</head>
<body>
	<div class="container">
		<form action="#" method="post">
			<input type="text" name="nickname">
			<input type="submit" value="Войти">
			<input type="password" name="password">
		</form>
	</div>

	<?php
		if (!empty($_POST['nickname']) && !empty($_POST['password'])) {
			require_once('include/db_access.php');

			$sql = "SELECT *
					FROM users
					WHERE nickname = '{$_POST['nickname']}' AND password = '{$_POST['password']}'";
			$result = send_sql($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				// User authorized
				$_SESSION['authorized'] = 1;
				$user = mysqli_fetch_assoc($result);
				$_SESSION['id'] = $user['id'];
				$_SESSION['nickname'] = $user['nickname'];
				$_SESSION['name'] = $user['name'];
				$_SESSION['about'] = $user['about'];
				$_SESSION['avatar'] = $user['avatar'];

				// Change user status (user is online now)
				send_sql($conn, "UPDATE users
								SET status = 'на связи'
								WHERE id = {$user['id']}");

				header('Location: friends.php');
			}
			else {
				echo 'Не удаётся войти';
			}
		}
	?>

	<!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</body>
</html>
