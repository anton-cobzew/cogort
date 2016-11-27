<?php
	session_start();
	if (!isset($_SESSION['authorized']))
		header('Location: login.php');
  if (empty($_GET['f']))
    header('Location: friends.php');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<?php include('include/html_default_head.php'); ?>
	<title>Сообщения | Cogort</title>
	<link rel="stylesheet" href="css/messages.css">
</head>
<body>

	<?php
		require('include/head_menu.php');
	?>

  <?php
    require_once('include/db_access.php');
    // Get friend by id
    $friend = mysqli_fetch_assoc(send_sql($conn,
            "SELECT id, name, status, about
            FROM users
            WHERE id = {$_GET['f']}"));
  ?>

  <p>
    <?php echo $friend['name']; ?>
  </p>
  <?php
		$messages_table = '';
		if ($_SESSION['id'] < $friend['id'])
			$messages_table = "messages_u{$_SESSION['id']}_u{$friend['id']}";
		else
			$messages_table = "messages_u{$friend['id']}_u{$_SESSION['id']}";
		// Check if messages table exists
		if (mysqli_num_rows(send_sql($conn, "SHOW TABLES LIKE '$messages_table'")) == 0)
			// Messages table does not exist. Create it
			send_sql($conn, "CREATE TABLE $messages_table
							(id INT NOT NULL AUTO_INCREMENT,
							id_from INT NOT NULL,
							id_to INT NOT NULL,
							text VARCHAR(100000) NOT NULL,
							datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
							status VARCHAR(20) NOT NULL,
							PRIMARY KEY (id))");

		/*
			Get messages
		*/
    $messages = send_sql($conn,
            "SELECT *
            FROM $messages_table
            ORDER BY datetime");
    while ($message = mysqli_fetch_assoc($messages)) {
			// Change message status to 'read'
			send_sql($conn,
						"UPDATE $messages_table
						SET status = 'read'
						WHERE id = {$message['id']}");
			$message_from = '';
			if ($message['id_from'] == $_SESSION['id']) {
				// This is message FROM me
				$message_from = $_SESSION['name'];
			}
			else {
				// This is message TO me
				$message_from = $friend['name'];
			}
      echo "<div class='message'>
              <div class='message__info'>
                <span class='message__from'>$message_from</span>
								<span class='message__datetime'></span>
								<script>
									// Convert datetime to local time zone
									$('.message__datetime').last().text(formatMessageDateTime('{$message['datetime']}'));
								</script>
								<p class='message__text'>{$message['text']}</p>
              </div>
            </div>";
    }


		/*
			Send message
		*/
		if (!empty($_POST['message-text'])) {
			send_sql($conn,
							"INSERT INTO $messages_table (id_from, id_to, text, status)
							VALUES ({$_SESSION['id']}, {$friend['id']}, '{$_POST['message-text']}', 'unread')");
			header("Refresh:0");
		}
  ?>

	<form class="message-form" action="#" method="post">
		<input type="text" name="message-text" placeholder="Введите сообщение">
		<input type="submit" value="Отправить">
	</form>
</body>
</html>
