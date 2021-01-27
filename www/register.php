<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : index.php
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

include_once 'assets/php/Registration_Process.php';
include_once 'assets/php/mySQL.php';
include_once 'assets/php/utils.inc.php';
include_once 'assets/php/HTML.php';

//Connexion à la base de donnée
$dbLink = connect_db();

$pseudo = NULL;
$e_mail = NULL;
$pwd = NULL;

//Si on a voulu s'incrire, on recupere les valeurs dans le formulaire
if(isset($_POST['pseudo']) && isset($_POST['e_mail']) && isset($_POST['pwd'])) {
    $pseudo = $_POST['pseudo'];
    $e_mail = $_POST['e_mail'];
    $pwd = $_POST['pwd'];
}
TopPage('login.css');
?>

    <div class="login">
        <h1>Inscription</h1>
        <form action="register.php" method="post">
            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="pseudo" placeholder="Pseudo" required>
            <label for="username">
                <i class="fas fa-at"></i>
            </label>
            <input type="email" name="e_mail" placeholder="Email" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="pwd" placeholder="Mot de passe" required>
            <input type="submit" name="action" value="S'inscrire">
        </form>
    </div>
</body>
</html>


<?php
//Si on a voulu s'inscrire, on execute la fonction d'inscription
if(isset($_POST['pseudo']) && isset($_POST['e_mail']) && isset($_POST['pwd'])) {
    $result = inscription($pseudo, $e_mail, $pwd, $dbLink);
    display_error('action', $result);
}

//Couper la connexion avec la BDD
mysqli_close($dbLink);