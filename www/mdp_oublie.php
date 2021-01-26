<?php
// Création ou restauration de la session
// Si déja connecté on fait une redirection vers : home.php
if (isset($_SESSION['loggedin'])) {
    header('Location: home.php');
    exit;
}

include_once 'assets/php/utils.inc.php';
start_page('Mot de passe oublié');

echo '
<!-- Body -->' ,PHP_EOL, '
<div class="login">' ,PHP_EOL, '
    <h1>Mot de passe oublié</h1>' ,PHP_EOL, '
    <form action="envoie_mail_mdp.php" method="post">' ,PHP_EOL, '
        <label for="username">' ,PHP_EOL, '
            <i class="fas fa-user"></i>' ,PHP_EOL, '
        </label>' ,PHP_EOL, '
        <input type="text" name="adresse" placeholder="Entrer votre Adresse mail" id="adresse">' ,PHP_EOL, '
		<div class="recevoir_mail"> Un mail contenant les instructions à suivre va vous être envoyé. Si vous ne le recevez pas, vérifiez que vous avez bien orthographié votre adresse.</div>' ,PHP_EOL, ' 
        <a class="mdpoublie" href="login.php"> Retour </a>' ,PHP_EOL, '
	<input type="submit" value="Envoyer">' ,PHP_EOL, '
    </form>' ,PHP_EOL, '
</div>' ,PHP_EOL, '
<a href="" id="scrollUp" class="invisible"></a>' ,PHP_EOL;
end_page();
?>
