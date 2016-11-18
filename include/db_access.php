<?php
	$server = 'localhost';
	$username = 'u686165995_user';
	$password = '2829155';
	$db_name = 'u686165995_main';

	$conn = mysqli_connect($server, $username, $password, $db_name);
	if (!$conn)
		die('Не удаётся подключиться к базе данных: ' . mysqli_connect_error());


	function send_sql($conn, $request) {
		$result = mysqli_query($conn, $request);
		if (!$result) {
			echo '<b>SQL request failed</b>: ' . $request . '<br>' . mysqli_error($conn);
		} else {
			return $result;
		}
	}
?>