<?php
// Création ou restauration de la session
session_start();

// Si on est pas connecté on fait une redirection vers : login.php
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

include_once 'assets/php/utils.inc.php';
include_once 'assets/php/change_param.php';
include_once 'assets/php/registration_db.php';
start_page('Compte');

$dbLink = connect_db();

$role = get_role($dbLink, $_SESSION['user_id']);

$result_pwd = NULL;
$result_param = NULL;
$result_updade = NULL;
$result_delete = NULL;
$result_insert = NULL;

if(isset($_POST['action_change_pwd'])) {
    $old_pwd = $_POST['old_pwd'];
    $new_pwd = $_POST['new_pwd'];

    if($old_pwd == $_SESSION['password']) {
        $result_pwd = change_pwd($new_pwd, $dbLink);
    }
    else {
        $result_pwd = 'Mauvais mot de passe';
    }
}

if($role == 'SUPER') {

    if (isset($_POST['action_param'])) {
        $n_msg = $_POST['n_msg'];
        $n_min = $_POST['n_min'];
        $n_max = $_POST['n_max'];

        $result_param = change_param($n_msg, $n_min, $n_max, $dbLink);

    } elseif (isset($_POST['action_update'])) {
        $id_user = $_POST['id_user'];
        $pseudo = $_POST['pseudo'];
        $email = $_POST['email'];

        $result_updade = update_user($id_user, $pseudo, $email, $dbLink);

    } elseif (isset($_POST['action_delete'])) {
        $pseudo = $_POST['pseudo'];
        $id_user = $_POST['id_user'];

        $result_delete = delete_user($pseudo, $id_user, $dbLink);
    } elseif (isset($_POST['action_insert'])) {
        $pwd = $_POST['pwd'];
        $pseudo = $_POST['pseudo'];
        $email = $_POST['email'];

        $result_insert = inscription($pseudo, $email, $pwd, $dbLink);
    }
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
    <h1 class="titre">Paramètres</h1>
    <!--            Titre end          -->

    <!--            Page            -->
    <div class="page">

        <!--            Main            -->
        <div class="main">

            <section class="compte">
                <p>Les détails de votre compte sont ci-dessous</p>

                <p><b>Pseudo : </b><?php echo $_SESSION['pseudo']?></p>

                <p><b>Email : </b><?php echo $_SESSION['name']?></p>

                        <form action="parametre.php" method="post">

                            <label>Ancien mot de passe :
                                <input type="password" name="old_pwd" required/><br/>
                            </label>

                            <label>Nouveau mot de passe :
                                <input type="password" name="new_pwd" required/><br/>
                            </label>

                            <input type="submit" name="action_change_pwd" value="Modifier le mot de passe"/>

                        </form>
            </section>

            <?php display_error('action_change_pwd', $result_pwd) ?>

            <?php if($role == 'SUPER') { ?>
                    <section class="param">
                        <p>Paramètres de l'application</p>

                        <?php
                            display_param($dbLink);
                        ?>

                    </section>

                    <?php display_error('action_param', $result_param) ?>

                    <section class="membres">
                        <p>Membres du site (Pseudo, Email)</p>

                        <div>

                            <?php display_membres($dbLink); ?>

                            <form action="parametre.php" method="post">
                                <input type="text" name="pseudo" required/>
                                <input type="email" name="email" required/>
                                <input type="password" name="pwd" required/>
                                <input type="submit" name="action_insert" value="Ajouter"/>
                            </form>

                    </div>
                </section>

                <?php display_error('action_insert', $result_insert) ?>
                <?php display_error('action_update', $result_updade) ?>
                <?php display_error('action_delete', $result_delete) ?>

            <?php }?>

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
