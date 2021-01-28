<?php

include_once 'assets/php/display_HTML.php';
include_once 'assets/php/mySQL.php';
include_once 'assets/php/login_process.php';

session_start();

session_destroy();

//connexion a la BDD
$dbLink = connect_db();

$e_mail = NULL;
$pwd = NULL;

//Si on a voulu se connecter, on recupere les valeurs dans le formulaire
if(isset($_POST['email']) && isset($_POST['password'])) {
    $e_mail = $_POST['email'];
    $pwd = $_POST['password'];

}

TopPage('login.css');
?>
        <div class="item login_nav">
            <div class="swap">
                <a class="swapitem login select">Login</a>
                <a class="swapitem login noselect" href="index.php"><i class="fa fa-home"></i></a>
                <a class="swapitem register noselect" href="register.php">Inscription</a>
            </div>
            <form action="login.php" method="post">
                <label for="email">
                    <i class="fas fa-user"></i>
                </label>
                <input type="email" name="email" placeholder="Email" required>
                <label for="password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" name="password" placeholder="Mot de passe" required>

                <a href="mdp_oublie.php" class="mdpoublie">Mot de passe oublié ?</a>

                <input type="submit" name="action" value="Login">

            </form>

            <?php
            //Si on a voulu se connecter, on execute la fonction de login
            if(isset($_POST['email']) && isset($_POST['password'])) {
                $result = login_process($e_mail, $pwd, $dbLink);
                display_error('action', $result);
            }
            ?>

        </div>
    </body>
</html>

<?php

//Couper la connexion avec la BDD
mysqli_close($dbLink);