<?php
// Création ou restauration de la session
session_start();

// Si on est pas connecté on fait une redirection vers : login.php
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

include_once 'assets/php/mySQL.php';

$con = OpenCon();
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT PSWD, EMAIL FROM USERS WHERE ID_USER = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['ID_USER']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mon Profil</title>
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
        if(isset($_SESSION['EMAIL'])) {
            echo "\t\t" .'<a href="login.php"><i class="fas fa-sign-in-alt"></i>Se connecter</a>' . PHP_EOL;
            echo "\t\t" .'<a href="registration.php"><i class="fa fa-user-plus"></i>S\'inscrire</a>' . PHP_EOL;
        }
        else {
            echo "\t\t" . '<a href="profile.php"><i class="fas fa-user-circle"></i>Mon Compte</a>' . PHP_EOL;
            echo "\t\t" . '<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>' . PHP_EOL;
        }
        ?>
    </div>
</nav>
<div class="content">
    <h2>Mon Compte</h2>
    <div>
        <p>Les détails de votre compte sont ci-dessous :</p>
        <table>
            <tr>
                <td>Pseudo :</td>
                <td><?=$_SESSION['pseudo']?></td>
            </tr>
            <tr>
                <td>Email :</td>
                <td><?=$_SESSION['name']?></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td><?=$_SESSION['password']?></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
