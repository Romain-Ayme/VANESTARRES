<?php
    // Création ou restauration de la session
    session_start();

    include_once 'assets/php/utils.inc.php';
    start_page('Vanestarre');

    //Connexion à la base de donnée
    $dbLink = connect_db();

    $tag = NULL;
    $role = NULL;

    //Obtenir le nombre de message à afficher par page
    $nb_max_msg = get_n_mes($dbLink);

    //numero de la page de base
    $page_number = 1;

    //Si on est connecté, on récupère le rôle de l'utilisateur
    if(isset($_SESSION['loggedin'])) {
        $role = get_role($dbLink, $_SESSION['user_id']);
    }

    //Si on a le numero de la page dans l'url, on affiche la page correspondante
    if(isset($_GET['page']))
        $page_number = (int) $_GET['page'];

    //Si on a le tag a à rechercher dans l'url, la variable tag récupère la valeur
    if(isset($_GET['search']))
        $tag = $_GET['search'];

?>

    <!-- Body -->

    <!--            Header          -->
        <nav class="navtop">
            <div>
                <img class="logo" alt="" src="assets/Images/VANESTARRE.png"/>
                <h1>anestarre</h1>
                <?php navbar(); ?>
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
                <?php tendance($dbLink); ?>
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

            <?php if(isset($_SESSION['loggedin']) && $role == 'SUPER') { ?>
                <section>
                    <form action="creation_msg.php" method="post">
                        <input type="submit" value="Ajouter un message">
                    </form>
                </section>
            <?php } ?>

            <?php $nb_ligne = display_msg($dbLink, $tag, $page_number, $nb_max_msg, $role); ?>

            <section>
                <?php pagination($tag, $page_number, $nb_ligne, $nb_max_msg); ?>
            </section>
        </div>
        <!--            Main end            -->

    </div>
    <!--            Page end            -->

    <a href="" id="scrollUp" class="invisible"></a>

<!-- Body end -->

<?php
    //Couper la connexion avec la BDD
    mysqli_close($dbLink);

    end_page();
?>