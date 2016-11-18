<?php
	session_start();
	if (isset($_SESSION['authorized'])) {
		// Change user status to offline
		require_once('include/db_access.php');
		send_sql($conn, "UPDATE users
						SET status = 'вне системы' 
						WHERE id = " . $_SESSION['id']);
		
		session_unset();
		session_destroy();
		
		header('Location: login.php');
	}
?>