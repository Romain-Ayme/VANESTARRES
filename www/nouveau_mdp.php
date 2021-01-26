<?php
	session_start();
	if (isset($_SESSION['idmdp'])) {	//Vérification: Si la session est lancé par quelqu'un ayant oublié son mdp (idmdp non vide), alors on continue
include_once 'assets/php/utils.inc.php';
start_page('Changer de Mot de passe');
		//Début html
echo '
<!-- Body -->', PHP_EOL, '
<div class="login">', PHP_EOL, '
    <h1>Changer de mot de passe</h1>', PHP_EOL, '
    <form action="change_mdp.php" method="post">', PHP_EOL, '
        <label for="username">', PHP_EOL, '
            <i class="fas fa-user"></i>', PHP_EOL, '
        </label>', PHP_EOL, '
        <input type="password" name="pass1" placeholder="Entrez votre nouveau mot de passe" id="pass1" required>', PHP_EOL,	//Entrer Mot de passe N°1
        '<label for="password">', PHP_EOL, '
            <i class="fas fa-lock"></i>', PHP_EOL, '
        </label>', PHP_EOL, '
        <input type="password" name="pass2" placeholder="Confirmez votre nouveau mot de passe" id="pass2" required>', PHP_EOL; // Entrer Mot de passe N°2
	if (isset($_SESSION['erreur'])) {		// Si les deux mdp ne sont les mêmes (voir change_mdp.php), afficher une erreur
		echo '<div class="texte"> Veuillez tapez le même mot de passe deux fois </div>';
		unset($_SESSION['erreur']);	// Suppression l'erreur afin de ne pas l'afficher Ad vitam æternam
	}
        echo '<input type="submit" value="Valider">', PHP_EOL,	//Bouton Valider
    '</form>', PHP_EOL, '
</div>', PHP_EOL;
end_page();
	}
	else {	// voir le if plus. Si la session est lancé par une personne n'ayant oublié son mdp, redirection vers index.php
		header('location: index.php');
	}
?>
