<?php
// Création ou restauration de la session
session_start();

include_once 'assets/php/mySQL.php';
include_once 'assets/php/utils.inc.php';
start_page('Vanestarre');

$dbLink = connect_db();
$tag = NULL;

$nb_max_msg = get_n_mes($dbLink);
$page_number = 1;

if(isset($_GET['page']))
    $page_number = (int) $_GET['page'];


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
    <h1 class="titre">Accueil</h1>
    <!--            Titre end          -->

    <!--            Page            -->
    <div class="page">

        <!--            Mp            -->
        <div class="mp">
            <section>
                En travaux... (profil ?)
            </section>
        </div>
        <!--            Mp end            -->

        <div class="tag_and_footer">

            <!--            Recherche            -->
            <section class="recherche">
                <form action="index.php" method="get">
                    <label> Recherche :
                        <input type="search" name="search">
                        <input type="submit" value="Rechercher">
                    </label>
                </form>
            </section>
            <!--            Recherche end            -->

            <!--            Tendance            -->
            <section class="tendance">
                <p>Top des ß : </p>
                <?php
                    tendance($dbLink);
                ?>
            </section>
            <!--            Tendance end            -->

            <!--            Footer            -->
            <section class="footer">
                <p> Copyright © 2021 Vanestarre. Tous droits réservés.</p>
            </section>
            <!--            Footer end            -->

        </div>

        <!--            Main            -->
        <div class="main">
            <?php
                $nb_ligne = display_msg($dbLink, $tag, $page_number, $nb_max_msg);
            ?>

            <section>
                <?php
                    pagination($tag, $page_number, $nb_ligne, $nb_max_msg);
                ?>
            </section>
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