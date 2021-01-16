<?php
// Création ou restauration de la session
session_start();

// Si on est pas connecté on fait une redirection vers : login.php
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
include_once 'assets/php/mySQL.php';
include_once 'assets/php/utils.inc.php';
start_page('Compte');
?>
    <!-- Body -->

    <!--            Header          -->
    <nav class="navtop">
        <div>
            <img class="logo" alt="" src="assets/Images/VANESTARRE.png"/>
            <h1>anestarre</h1>
            <?php
            echo '<a href="index.php"><i class="fa fa-home"></i>Accueil</a>' . PHP_EOL;
            if(isset($_SESSION['loggedin'])) {
                echo "\t\t" . '<a href="profile.php"><i class="fa fa-cog"></i>Paramètres</a>' . PHP_EOL;
                echo "\t\t" . '<a href="home.php"><i class="fas fa-user-circle"></i>Mon Compte</a>' . PHP_EOL;
                echo "\t\t" . '<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>' . PHP_EOL;
            }
            else {
                echo "\t\t" .'<a href="login.php"><i class="fas fa-sign-in-alt"></i>Se connecter</a>' . PHP_EOL;
                echo "\t\t" .'<a href="registration.php"><i class="fa fa-user-plus"></i>S\'inscrire</a>' . PHP_EOL;
            }
            ?>
        </div>
    </nav>
    <!--            Header end          -->

    <!--            Titre          -->
    <h1 class="titre">Paramètres</h1>
    <!--            Titre end          -->

    <!--            Page            -->
    <div class="page">

        <!--            Main            -->
        <div class="main">

            <section class="compte">
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
            </section>

        </div>
        <!--            Main end            -->

    </div>
    <!--            Page end            -->

    <a href="" id="scrollUp" class="invisible"></a>

    <!-- Body end -->

<?php
end_page();
?>
