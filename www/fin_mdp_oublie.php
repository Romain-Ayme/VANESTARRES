<?php
	session_start();	//Récuperation de la session et destruction de celle-ci
	session_destroy();
	include_once 'assets/php/utils.inc.php';
	start_page('Mot de passe enregristré');
		echo '
		<!-- Body -->', PHP_EOL, '
    			<div class = "login">' , PHP_EOL, '
				<div class = "texte"> Votre nouveau mot de passe a été enregistré. <a href="login.php" class="texte">Retour</a> </div>', PHP_EOL, '
			</div>', PHP_EOL;
		end_page();
?>
