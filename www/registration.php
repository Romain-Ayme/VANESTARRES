<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : index.php
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

include_once 'assets/php/registration_db.php';
include_once 'assets/php/utils.inc.php';
start_page('Inscription');

$dbLink = connect_db();

$pseudo = NULL;
$e_mail = NULL;
$pwd = NULL;

if(isset($_POST['pseudo']) && isset($_POST['e_mail']) && isset($_POST['pwd'])) {
    $pseudo = $_POST['pseudo'];
    $e_mail = $_POST['e_mail'];
    $pwd = $_POST['pwd'];
}
?>

    <!-- Body -->

    <!--            Header          -->
    <nav class="navtop">
        <div>
            <img class="logo" alt="" src="assets/Images/VANESTARRE.png"/>
            <h1>anestarre</h1>
            <?php
            navbar();
            ?>
        </div>
    </nav>
    <!--            Header end          -->

    <!--            Titre          -->
    <h1 class="titre">Inscription</h1>
    <!--            Titre end          -->

    <!--            Page            -->
    <div class="page">

        <!--            Main            -->
        <div class="main">
            <section class="inscription">
                <form action="registration.php" method="post">

                    <label>Pseudo :
                        <input type="text" name="pseudo" required/><br/>
                    </label>

                    <label>E-mail :
                        <input type="email" name="e_mail" required/><br/>
                    </label>

                    <label>Mot de passe :
                        <input type="password" name="pwd" required/><br/>
                    </label>

                    <input type="submit" name="action" value="S'inscrire"/>
                </form>
            </section>

                <?php
                    if(isset($_POST['pseudo']) && isset($_POST['e_mail']) && isset($_POST['pwd'])) {
                        $result = inscription($pseudo, $e_mail, $pwd, $dbLink);
                        display_error('action', $result);
                    }
                ?>
        </div>
        <!--            Main end            -->

    </div>
    <!--            Page end            -->

    <a href="" id="scrollUp" class="invisible"></a>

    <!-- Body end -->

<?php
mysqli_close($dbLink);
end_page();
?>