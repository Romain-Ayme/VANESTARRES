<?php

    // Création ou restauration de la session
    session_start();

    // Si on est pas connecté on fait une redirection vers : login.php
    if (!isset($_SESSION['loggedin'])) {
        header('Location: login.php');
        exit;
    }

    include_once 'assets/php/utils.inc.php';

    start_page('Nouveau message');

    //Connexion à la base de donnée
    $dbLink = connect_db();

    $id_msg = NULL;

    if(isset($_POST['id_m'])) {
        $id_msg = $_POST['id_m'];

        $query = 'SELECT MESSAGE, IMG FROM messages WHERE ID_MESSAGE = ' . $id_msg;
        $dbResult = execute_query($dbLink, $query);

        $dbRow = mysqli_fetch_assoc($dbResult);
        $msg = $dbRow['MESSAGE'];
        $img_path = $dbRow['IMG'];
    }

    else {
        $msg = NULL;
        $img_path = NULL;
    }

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
    <h1 class="titre"><?php if($id_msg == NULL) echo 'Nouveau message'; else echo 'Modification du message'; ?></h1>
    <!--            Titre end          -->

    <!--            Page            -->
    <div class="page">

        <!--            Main            -->
        <div class="main">
            <section class="nouv_msg">
                <form action="assets/php/insertion_db.php" method="post" enctype="multipart/form-data">

                    <input type="text" name="msg" placeholder="Ecrit ton message ici..." value="<?php echo $msg ?>" maxlength="50"><br/>

                    <img class="image" alt="" src="<?php echo $img_path ?>"/>

                    <input type="file" name="img" accept=".png, .jpg, .jpeg, .gif"><br/>

                    <input type="hidden" name ="id_m" value="<?php echo $id_msg ?>"/>

                    <input type="submit" value="Envoyer">

                </form>
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
