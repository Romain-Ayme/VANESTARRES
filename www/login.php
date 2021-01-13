<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : home.php
if (isset($_SESSION['loggedin'])) {
    header('Location: home.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="icon" type="image/png" href="assets/img/VANESTARRE.png" />
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login">
    <h1>Login</h1>
    <form action="authentication.php" method="post">
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
</body>
</html>