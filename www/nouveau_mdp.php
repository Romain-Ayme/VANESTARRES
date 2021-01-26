<?php
	session_start();
	if (isset($_SESSION['idmdp'])) {
include_once 'assets/php/utils.inc.php';
start_page('Changer de Mot de passe');
echo '
<!-- Body -->', PHP_EOL, '
<div class="login">', PHP_EOL, '
    <h1>Changer de mot de passe</h1>', PHP_EOL, '
    <form action="change_mdp.php" method="post">', PHP_EOL, '
        <label for="username">', PHP_EOL, '
            <i class="fas fa-user"></i>', PHP_EOL, '
        </label>', PHP_EOL, '
        <input type="password" name="pass1" placeholder="Entrez votre nouveau mot de passe" id="pass1" required>', PHP_EOL, '
        <label for="password">', PHP_EOL, '
            <i class="fas fa-lock"></i>', PHP_EOL, '
        </label>', PHP_EOL, '
        <input type="password" name="pass2" placeholder="Confirmez votre nouveau mot de passe" id="pass2" required>', PHP_EOL;
	if ($_SESSION['erreur'] == 1) {
		echo '<div class="texte"> Veuillez tapez le même mot de passe deux fois </div>';
		$_SESSION['erreur'] = 0;
	}
        echo '<input type="submit" value="Valider">', PHP_EOL, '
    </form>', PHP_EOL, '
</div>', PHP_EOL;
end_page();
	}
	else {
		header('location: index.php');
	}
?>