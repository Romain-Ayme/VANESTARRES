<?php
	include_once 'assets/php/utils.inc.php';
	session_start();
	$email = $_POST['adresse'];	//Récuperation d'email dans le formulaire de mdp_oublie.php
	$query = 'SELECT * FROM users WHERE EMAIL = \''. $email .'\' '; //Ecriture de la requête SQL
	$dbLink = connect_db(); //Connexion à la base
	$dbResult = execute_query($dbLink, $query); //Execution de la requête
	$dbRow = mysqli_fetch_assoc($dbResult); //Récuperation du résultat
	if (isset($dbRow)) {	//Si résultat trouvé
		mail($email, 'Mot de passe oublié', 'Bonjour. Vous avez effectuer une demande de nouveau mot de passe.
						Veuillez vous rendre au lien suivant:
						http://romain-ayme.alwaysdata.net/nouveau_mdp.php
						Si vous n\'êtes pas à l\'origine de cette demande, démerdez vous
						pour trouver le coupable.');
		$_SESSION['idmdp'] = $dbRow['ID_USER']; //idmp prend la valeur de l'id de l'utilisateur (servira pour nouveau_mdp.php)
	}
	start_page('Mail Mot de passe oublié'); //Page html
	echo '
	<!-- Body -->', PHP_EOL, '
    		<div class = "login">' , PHP_EOL, '
			<div class = "texte"> Un email vous a été envoyé. </div>', PHP_EOL, '
		</div>', PHP_EOL; // Pour des raisons de sécurité, on ne précise pas si l'adresse tapée existe dans la base
	end_page();
?>
