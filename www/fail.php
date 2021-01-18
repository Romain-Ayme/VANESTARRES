<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : sucess.php
if (isset($_SESSION['loggedin'])) {
    header('Location: sucess.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Fail Page</title>
    <link rel="icon" type="image/png" href="assets/img/VANESTARRE.png" />
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Vanestarre</h1>
        <?php
        echo '<a href="index.php"><i class="fa fa-home"></i>Accueil</a>' . PHP_EOL;
        if(isset($_SESSION['loggedin'])) {
            echo "\t\t" . '<a href="profile.php"><i class="fas fa-user-circle"></i>Mon Compte</a>' . PHP_EOL;
            echo "\t\t" . '<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>' . PHP_EOL;
        }
        else {
            echo "\t\t" .'<a href="login.php"><i class="fas fa-sign-in-alt"></i>Se connecter</a>' . PHP_EOL;
            echo "\t\t" .'<a href="registration.php"><i class="fa fa-user-plus"></i>S\'inscrire</a>' . PHP_EOL;
        }
        ?>
    </div>
</nav>
<div class="content">
    <h2>Fail Page</h2>
    <p>Mauvais Email et/ou Mot de passe </p>
</div>
</body>
</html>
