<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : sucess.php
if (isset($_SESSION['loggedin'])) {
    header('Location: sucess.php');
    exit;
}

include_once 'assets/php/utils.inc.php';
start_page('fail');
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
    <h1 class="titre">Erreur !</h1>
    <!--            Titre end          -->

    <!--            Page            -->
    <div class="page">

        <!--            Main            -->
        <div class="main">
            <section>
                <p>Mauvais Email et/ou Mot de passe </p>
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
