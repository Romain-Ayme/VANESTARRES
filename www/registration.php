<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : profil.php
if (isset($_SESSION['loggedin'])) {
    header('Location: profil.php');
    exit;
}

include_once 'assets/php/utils.inc.php';

start_page('main');

//phpinfo();

?>

    <form action="data-processing.php" method="post">
        <label>Identifiant :
            <input type="text" name="id"/><br/>
        </label>
        <label>Civilité (sexe) :
            <input type="radio" name="sexe" value="femme"/>Femme
            <input type="radio" name="sexe" value="homme"/>Homme<br/>
        </label>
        <label>E-mail :
            <input type="text" name="e_mail"/><br/>
        </label>
        <label>Mot de passe :
            <input type="password" name="mdp"/><br/>
        </label>
        <label>Vérification du mot de passe :
            <input type="password" name="check_mdp"/><br/>
        </label>
        <label>Téléphone :
            <input type="text" name="tel"/><br/>
        </label>
        <label>Pays :
            <select name="pays">
                <option value="France">France</option>
                <option value="Angleterre">Angleterre</option>
                <option value="Italie">Italie</option>
                <option value="USA">USA</option>
            </select><br/>
        </label>
        <label>Conditions générales
            <input type="checkbox" name="cg" required/><br/>
        </label>
        <input type="submit" name="action" value="mailer"/><br/>
    </form>

<?php
end_page();