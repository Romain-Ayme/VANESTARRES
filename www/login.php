<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : index.php
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

include_once 'assets/php/utils.inc.php';
start_page('Connexion');
?>
<!-- Body -->
<div class="login">
    <h1>Login</h1>
    <form action="authentification.php" method="post">
        <label for="username">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" name="email" placeholder="Email" id="username" required>
        <label for="password">
            <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="password" placeholder="Mot de passe" id="password" required>
        <input type="submit" value="Login">
    </form>
</div>
<a href="" id="scrollUp" class="invisible"></a>
<!-- Body end -->
<?php
end_page();
?>
