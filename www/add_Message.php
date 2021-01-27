<?php

// Création ou restauration de la session
session_start();

// Si on est pas connecté on fait une redirection vers : login.php
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

include_once 'assets/php/utils.inc.php';
include_once 'assets/php/HTML.php';
include_once 'assets/php/mySQL.php';

topPage();

//Connexion à la base de donnée
$dbLink = connect_db();

//Si on est connecté, on récupère le rôle de l'utilisateur
if(isset($_SESSION['loggedin'])) {
    $role = get_role($dbLink, $_SESSION['user_id']);
}

$id_msg = NULL;

if(isset($_POST['id_m'])) {
    $id_msg = $_POST['id_m'];

    if(isset($_POST['supprimer'])) {

        delete_linked_tag($id_msg, $dbLink);

        delete_note($id_msg, $dbLink);

        delete_img($id_msg);

        $query = 'DELETE FROM messages WHERE ID_MESSAGE = ' . $id_msg;
        execute_query($dbLink, $query);

        //On retourne sur la page d'avant
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    else {

        $query = 'SELECT MESSAGE, IMG FROM messages WHERE ID_MESSAGE = ' . $id_msg;
        $dbResult = execute_query($dbLink, $query);

        $dbRow = mysqli_fetch_assoc($dbResult);
        $msg = $dbRow['MESSAGE'];
        $img_path = $dbRow['IMG'];
    }
}

else {
    $msg = NULL;
    $img_path = NULL;
}

navPage($role);

?>

        <!--            Main            -->
        <div class="main">
            <section class="nouv_msg">
                <form action="assets/php/Add_Message_Process.php" method="post" enctype="multipart/form-data">

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

tagFooterPage($dbLink);

mysqli_close($dbLink);
