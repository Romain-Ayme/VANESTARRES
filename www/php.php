<?php

		$dbLink = mysqli_connect('mysql-romain-ayme.alwaysdata.net', '223609_php', 'zK7dQm4H3')
			or die('Erreur de connexion au serveur : ' . mysqli_connect_error());
		mysqli_select_db($dbLink , 'romain-ayme_vanestarre')
			or die('Erreur dans la sélection de la base : ' . mysqli_error($dbLink));
	
		$query = 'SELECT MESSAGE, ID_USER, NB_LOVE, NB_CUTE, NB_STYLE, NB_SWAG FROM messages';

		if(!($dbResult = mysqli_query($dbLink, $query))) {
        	echo 'Erreur de requête<br/>';
        	// Affiche le type derreur
        	echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
        	// Affiche la requête envoyée.
        	echo 'Requête : ' . $query . '<br/>';
        	exit();
        	}

		while($dbRow = mysqli_fetch_assoc($dbResult)) {
			$querynom = 'SELECT PSEUDO FROM users WHERE ID_USER ='. $dbRow['ID_USER'];
	
			if(!($dbResultNom = mysqli_query($dbLink, $querynom))) {
        		echo 'Erreur de requête<br/>';
        		// Affiche le type derreur
        		echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
        		// Affiche la requête envoyée.
        		echo 'Requête : ' . $querynom . '<br/>';
        		exit();
			}

			$dbRowNom = mysqli_fetch_row($dbResultNom);

			echo '<div class = TitreMsg>', $dbRowNom['PSEUDO'] ,'</div>', PHP_EOL; // Undefined index: PSEUDO
			echo '<div class = message>', $dbRow['MESSAGE'] ,'</div>', PHP_EOL;
			echo '<div class = like>', PHP_EOL,
				'<div class = love>', 
				$dbRow['NB_LOVE'], '<img class="reaction" src="assets/img/love.png">',
				'</div>', PHP_EOL ,

				'<div class = cute>',
				$dbRow['NB_CUTE'], '<img class="reaction" src="assets/img/cute.png">',
				'</div>', PHP_EOL ,

				'<div class = swag>',
				$dbRow['NB_SWAG'], '<img class="reaction" src="assets/img/swag.png">',
				'</div>', PHP_EOL ,

				'<div class = style>',
				$dbRow['NB_STYLE'], '<img class="reaction" src="assets/img/style.png">',
				'</div>', PHP_EOL;
        }
	
	?>
