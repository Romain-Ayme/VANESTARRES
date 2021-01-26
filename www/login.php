<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : home.php
if (isset($_SESSION['loggedin'])) {
    header('Location: home.php');
    exit;
}

include_once 'assets/php/utils.inc.php';
start_page('Connexion');

echo '
<!-- Body -->', PHP_EOL, '
<div class="login">', PHP_EOL, '
    <h1>Connexion</h1>', PHP_EOL;
	if(isset($_SESSION['erreur'])) {
		echo '<div class = "texte">Mauvais mot de passe / adresse</div>', PHP_EOL;
		unset($_SESSION['erreur']);
	}
    echo '<form action="authentification.php" method="post">', PHP_EOL, '
        <label for="username">', PHP_EOL, '
            <i class="fas fa-user"></i>', PHP_EOL, '
        </label>', PHP_EOL, '
        <input type="text" name="email" placeholder="Email" id="username" required>', PHP_EOL, '
        <label for="password">', PHP_EOL, '
            <i class="fas fa-lock"></i>', PHP_EOL, '
        </label>', PHP_EOL, '
        <input type="password" name="password" placeholder="Mot de passe" id="password" required>', PHP_EOL, '
	<a href="mdp_oublie.php" class="mdpoublie">Mot de passe oublié ?</a>', PHP_EOL, '
        <input type="submit" value="Connexion">', PHP_EOL, '
    </form>', PHP_EOL, '
</div>', PHP_EOL, '
<a href="" id="scrollUp" class="invisible"></a>', PHP_EOL;
end_page();
?>
