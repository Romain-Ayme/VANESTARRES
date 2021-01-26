<?php
	include_once 'assets/php/utils.inc.php';
	session_start();
	if (isset($_SESSION['idmdp'])) {
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];
		if ($pass1 != $pass2) {
			$_SESSION['erreur'] = 1;
			header('location: nouveau_mdp.php');
		}
		else {
		$pass1 = password_hash($pass1, PASSWORD_DEFAULT);
		$query = 'UPDATE users SET PSWD =\''. $pass1 .'\' WHERE ID_USER = '. $_SESSION['idmdp'];
		$dbLink = connect_db();
		execute_query($dbLink, $query);
		header('location: fin_mdp_oublie.php');
		}
	}
	else {
		echo 'Non';
	}
?>