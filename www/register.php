<?php

include_once 'assets/php/registration_Process.php';
include_once 'assets/php/mySQL.php';
include_once 'assets/php/display_HTML.php';

// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : index.php
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

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

        <div class="item login_nav">
            <div class="swap">
                <a class="swapitem login noselect" href="login.php">Login</a>
                <a class="swapitem login noselect" href="index.php"><i class="fa fa-home"></i></a>
                <a class="swapitem register select">Inscription</a>
            </div>
            <form action="register.php" method="post">

                <label>
                    <i class="fas fa-user"></i>
                </label>

                <input type="text" name="pseudo" placeholder="Pseudo" required>
                <label>
                    <i class="fas fa-at"></i>
                </label>

                <input type="email" name="e_mail" placeholder="Email" required>
                <label>
                    <i class="fas fa-lock"></i>
                </label>

                <input type="password" name="pwd" placeholder="Mot de passe" required>

                <?php
                //Si on a voulu s'inscrire, on execute la fonction d'inscription
                if(isset($_POST['pseudo']) && isset($_POST['e_mail']) && isset($_POST['pwd'])) {
                    $result = inscription($pseudo, $e_mail, $pwd, $dbLink);
                    display_error('action', $result);
                }
                ?>

                <input type="submit" name="action" value="S'inscrire">
            </form>

        </div>
    </body>
</html>

<?php

//Couper la connexion avec la BDD
mysqli_close($dbLink);