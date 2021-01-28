<?php

include_once 'assets/php/display_HTML.php';
include_once 'assets/php/mySQL.php';
include_once 'assets/php/login_process.php';

session_start();

session_destroy();

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
        <div class="login">
            <h1>Login</h1>
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