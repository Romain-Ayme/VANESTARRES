<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : home.php
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}
include_once 'assets/php/HTML.php';
TopPage('login.css');
?>
    <div class="login">
        <h1>Login</h1>
        <form action="assets/php/Login_Process.php" method="post">
            <label for="email">
                <i class="fas fa-user"></i>
            </label>
            <input type="email" name="email" placeholder="Email" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>