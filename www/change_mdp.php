<?php
	include_once 'assets/php/utils.inc.php';
	session_start();
	if (isset($_SESSION['idmdp'])) {	//Si idmdp est défini (l'utilisateur a fait une demande suite à un oubli de mot de passe)
		$pass1 = $_POST['pass1'];	//Récuperation des deux champs de mot de passe de nouveau_mdp.php
		$pass2 = $_POST['pass2'];
		if ($pass1 != $pass2) {		//Si les deux champs ne sont pas identiques
			$_SESSION['erreur'] = 1;	//Servira dans la page précédente (nouveau_mdp.php)
			header('location: nouveau_mdp.php');	//Renvoie à la page précédente
		}
		else {	// Si c'est bon
		$pass1 = password_hash($pass1, PASSWORD_DEFAULT);	//Chiffrage du mot de passe
		$query = 'UPDATE users SET PSWD =\''. $pass1 .'\' WHERE ID_USER = '. $_SESSION['idmdp']; //Ecriture de la requête (mise à jour du mot de passe)
		$dbLink = connect_db();		//Connexion à la base
		execute_query($dbLink, $query);		//Execution de la requête
		header('location: fin_mdp_oublie.php');		// Envoie sur fin_mdp_oublie.php
		}
	}
?>
