<?php
	session_start();
	if (isset($_SESSION['authorized']))
		header('Location: friends.php');
	else
		header('Location: login.php');
?>