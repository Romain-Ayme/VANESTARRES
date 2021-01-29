<?php

include_once 'assets/php/display_HTML.php';    //Inclusion des fonctions
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
$code = NULL;

//Si on a appuyer sur valider, on recupere les valeurs des formulaires
if (isset($_POST['action'])) {
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $code = $_POST['code'];

    //On execute la fonction pour changer le mot de passe
    $result = change_pwd($pass1, $pass2, $code, $dbLink);
}

//on recupère le code dans l'url
if (isset($_GET['code'])) {
    $code = $_GET['code'];
}

topPage('css.css');     // Fonction de début de page

?>

        <!--            Main            -->
        <div class="div_messages">

            <!--            Titre          -->
            <h1 class="titre">Mot de passe oublié</h1>
            <!--            Titre end          -->

            <div class="param">
                <form action="nouveau_mdp.php?code=<?php echo $code; ?>" method="post">

                    <label>
                        <input type="password" name="pass1" placeholder="Entrez votre nouveau mot de passe" id="pass1" required>
                    </label>

                    <label>
                        <input type="password" name="pass2" placeholder="Confirmez votre nouveau mot de passe" id="pass2" required>
                    </label>

                    <input type="submit" name="action" value="Valider">

                    <input type="hidden" name="code" value="<?php echo $code; ?>">

                </form>
            </div>

            <?php display_error('action', $result) ?>

        </div>
        <a href="" id="scrollUp" class="invisible"></a>
    </body>
</html>

<?php

//Couper la connexion avec la BDD
mysqli_close($dbLink);