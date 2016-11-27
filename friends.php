<?php
	session_start();
	if (!isset($_SESSION['authorized']))
		header('Location: login.php');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<?php include('include/html_default_head.php'); ?>
	<title>Друзья | Cogort</title>
	<link rel="stylesheet" href="css/friends.css">
</head>
<body>
	<?php
		require('include/head_menu.php');
	?>

	<ul class="friends_list">
		<?php
			require_once('include/db_access.php');

			// Get friends by id
			$friends_ids = array();
			$result = send_sql($conn,
							   "SELECT id2
								FROM friends
								WHERE id1 = " . $_SESSION['id']);
			while ($row = mysqli_fetch_assoc($result))
				$friends_ids[] = $row['id2'];
			$result = send_sql($conn,
							   "SELECT id1
								FROM friends
								WHERE id2 = " . $_SESSION['id']);
			while ($row = mysqli_fetch_assoc($result))
				$friends_ids[] = $row['id1'];


			// Get their information
			foreach ($friends_ids as $id) {
				$f = mysqli_fetch_assoc(send_sql($conn,
							   "SELECT name, status, about
							   FROM users
							   WHERE id = " . $id));

				$avatar_file = 'img/avatar/' . $id . '.jpg';
				if (!file_exists($avatar_file)) {
					$avatar_file = 'img/avatar/' . $id . '.png';
					if (!file_exists($avatar_file))
						$avatar_file = 'img/avatar/default.jpg';
				}

				echo '<a href="messages.php?f=' . $id . '">
								<li class="friends_list__item">
									<img class="friends_list__item__image" src="' . $avatar_file . '">
									<p class="friends_list__item__name">' . $f['name'] . '</p>
									<p class="friends_list__item__info">
										<span class="friends_list__item__info__status">' . $f['status'] . ' </span>|
										<span class="friends_list__item__info__about"> ' . $f['about'] . '</span>
										</p>
								</li>
							</a>';
			}
		?>
	</ul>
</body>
</html>
