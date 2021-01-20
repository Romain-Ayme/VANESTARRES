<?php
// Création ou restauration de la session
session_start();

// Si on est pas connecté on fait une redirection vers : login.php
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

include_once 'assets/php/utils.inc.php';
start_page('Vanestarre');

$dbLink = connect_db();
$tag = NULL;

if(isset($_GET['search']))
    $tag = $_GET['search'];

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
    <h1 class="titre">Mon Compte</h1>
    <!--            Titre end          -->

    <!--            Page            -->
    <div class="page">

        <!--            Mp            -->
        <div class="mp">
            <section>
                mp
            </section>
        </div>
        <!--            Mp end            -->

        <!--            Tag and footer            -->
        <div class="tag_and_footer">

            <!--            Recherche            -->
            <section class="recherche">
                <form action="home.php" method="get">
                    <label> Recherche :
                        <input type="search" name="search">
                        <input type="submit" value="Rechercher">
                    </label>
                </form>
            </section>
            <!--            Recherche end            -->

            <!--            Tendance            -->
            <section class="tendance">
                Top 1
            </section>
            <!--            Tendance end            -->

            <!--            Footer            -->
            <section class="footer">
                <p> Copyright © 2021 Vanestarre. Tous droits réservés.</p>
            </section>
            <!--            Footer end            -->

        </div>
        <!--            Tag and footer end            -->

        <!--            Main            -->
        <div class="main">
            <?php
                display_msg($dbLink, $tag);
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