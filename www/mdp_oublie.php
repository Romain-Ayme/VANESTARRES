<?php

//Inclusion des fonctions
include_once 'assets/php/display_HTML.php';
include_once 'assets/php/mySQL.php';
include_once 'assets/php/forgotten_pwd_process.php';

session_start();

// Si déja connecté on fait une redirection vers : index.php
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

//Connexion à la base de donnée
$dbLink = connect_db();

$result = NULL;

//Si on a appuyer sur envoyer, on recupere les valeurs des formulaires
if (isset($_POST['action'])) {
    $email = $_POST['email'];

    //On execute la fonction pour envoyer l'email
    $result = send_mail($email, $dbLink);
}

topPage('css.css');     // Fonction de début de page

?>

        <!--            Main            -->
        <div class="div_messages">

            <!--            Titre          -->
            <h1 class="titre">Mot de passe oublié</h1>
            <!--            Titre end          -->

            <div class="param">
                <form action="mdp_oublie.php" method="post">

                    <i class="fas fa-user"></i>
                    <label for="username">
                        <input type="text" name="email" placeholder="Entrer votre Adresse mail" id="adresse">
                    </label>

                    <?php display_error('action', $result) ?>

                    <a class="mdpoublie" href="login.php"> Retour </a>

                    <?php

                    //si on a deja appuyé sur le bouton envoyer, on ne l'affiche plus
                    if (!isset($_POST['action'])) {
                        echo '<input type="submit" name="action" value="Envoyer">';
                    }
                    ?>

                </form>
            </div>

        </div>
        <a href="" id="scrollUp" class="invisible"></a>
    </body>
</html>

<?php

//Couper la connexion avec la BDD
mysqli_close($dbLink);
