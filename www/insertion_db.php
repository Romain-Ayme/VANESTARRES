<?php

    include_once "assets/php/utils.inc.php";

    // Création ou restauration de la session
    session_start();

    // Si on est pas connecté on fait une redirection vers : login.php
    if (!isset($_SESSION['loggedin'])) {
        header('Location: login.php');
        exit;
    }

    $dbLink = mysqli_connect('mysql-romain-ayme.alwaysdata.net', '223609_php', 'zK7dQm4H3')	//Connexion à la base
        or die('Erreur de connexion au serveur : ' . mysqli_connect_error());
    mysqli_select_db($dbLink , 'romain-ayme_vanestarre')
        or die('Erreur dans la sélection de la base : ' . mysqli_error($dbLink));

    $msg = $_POST['msg'];	//Récuperation du message

    $id_msg = insert_msg_db($_SESSION['user_id'], $msg, $dbLink); //Insertion du message dans la base (Contenu du message et id de l'utilisateur)

   manage_tag($msg, $dbLink, $id_msg);	//Création du tag (voir utils.inc.php)

	$repertoireDestination = 'assets/utilisateurs/', $_SESSION['user_id'] ,'/img_msg/,' $id_msg ,'/'; //Chemin du repertoire de stockage de l'image
	$nomDestination = nommage($_FILES["msg"]["type"]);	//Futur nom de l'image stocké

	$nom = $repertoireDestination, $nomDestination;		//Chemin complet

    	if (is_dir($nom)) {
                      echo 'Le message existe déjà';  
                      }
    	else { 
          mkdir($nom);	//Création du repertoire
          }

	if (is_uploaded_file($_FILES["img"]["tmp_name"])) {
		if (rename($_FILES["img"]["tmp_name"], $repertoireDestination.$nomDestination)) { //Déplacement de l'image + Changement de nom
			echo 'Fichier uploadé';
		}
		else {
			echo 'Erreur dans le déplacement du fichier dans le serveur';
		}
	}
	else {
		echo 'Erreur dans l'uploadage (terme approximatif) du fichier';
	}

	$query = 'INSERT INTO messages (IMG) VALUES (\'', $repertoireDestination, $nomDestination, '\')';	//Insertion du chemin dans la base
	execute_query($dbLink, $query);
?>
	
   header('Location: index.php');

?>
