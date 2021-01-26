<?php
	include_once 'assets/php/utils.inc.php';
	session_start();
	$email = $_POST['adresse'];
	$query = 'SELECT * FROM users WHERE EMAIL = \''. $email .'\' ';
	$dbLink = connect_db();
	$dbResult = execute_query($dbLink, $query);
	$dbRow = mysqli_fetch_assoc($dbResult);
	if (isset($dbRow)) {
		mail($email, 'Mot de passe oublié', 'Bonjour. Vous avez effectuer une demande de nouveau mot de passe.
						Veuillez vous rendre au lien suivant:
						http://romain-ayme.alwaysdata.net/nouveau_mdp.php
						Si vous n\'êtes pas à l\'origine de cette demande, démerdez vous
						pour trouver le coupable.');
		$_SESSION['idmdp'] = $dbRow['ID_USER'];
	}
	start_page('Mail Mot de passe oublié');
	echo '
	<!-- Body -->', PHP_EOL, '
    		<div class = "login">' , PHP_EOL, '
			<div class = "texte"> Un email vous a été envoyé. </div>', PHP_EOL, '
		</div>', PHP_EOL;
	end_page();
?>
