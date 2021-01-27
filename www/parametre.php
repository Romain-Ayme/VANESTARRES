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

    //Connexion à la base de donnée
    $dbLink = connect_db();

    ///On récupère le rôle de l'utilisateur
    $role = get_role($dbLink, $_SESSION['user_id']);

    $result_pwd = NULL;
    $result_param = NULL;
    $result_updade = NULL;
    $result_delete = NULL;
    $result_insert = NULL;

    //Si on a voulu changer le mot de passe, on recupere les valeurs des formulaires
    if(isset($_POST['action_change_pwd'])) {
        $old_pwd = $_POST['old_pwd'];
        $new_pwd = $_POST['new_pwd'];

        //Si le mot de passe dans le champ "ancien mot de passe" correspond à celui actuellement, on execute la fonction pour changer le mot de passe
        if($old_pwd == $_SESSION['password']) {
            $result_pwd = change_pwd($new_pwd, $dbLink);
        }
        //Sinon, le resultat = "mauvais mot de passe"
        else {
            $result_pwd = 'Mauvais mot de passe';
        }
    }

    //Si c'est un administrateur qui est connecter à cette page, on peut recuperer les autres paramètres
    if($role == 'SUPER') {

        //Si on a voulu changer les paramètres, on recupere les valeurs des formulaires
        if (isset($_POST['action_param'])) {
            $n_msg = $_POST['n_msg'];
            $n_min = $_POST['n_min'];
            $n_max = $_POST['n_max'];

            //On execute la fonction pour changer les paramètres
            $result_param = change_param($n_msg, $n_min, $n_max, $dbLink);
        }

        //Sinon, si on a voulu modifier le pseudo ou l'email de quelqu'un, on recupere les valeurs des formulaires
        elseif (isset($_POST['action_update'])) {
            $id_user = $_POST['id_user'];
            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];

            //On execute la fonction pour modifier le compte d'un utilisateur
            $result_updade = update_user($id_user, $pseudo, $email, $dbLink);
        }

        //Sinon, si on a voulu supprimer un utilisateur, on recupere les valeurs des forumaires
        elseif (isset($_POST['action_toggle'])) {
            $id_user = $_POST['id_user'];

            //On execute la fonction pour supprimer l'utilisateur
            $result_delete = toggle_user($id_user, $dbLink);
        }

        //Sinon, si on a voulu inserer un nouveau compte, on recupere les valeurs des formulaires
        elseif (isset($_POST['action_insert'])) {
            $pwd = $_POST['pwd'];
            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];

            //On execute la fonction pour inscrire le nouveau compte
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
            <?php navbar(); ?>
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

                        <?php display_param($dbLink); ?>

                    </section>

                    <?php display_error('action_param', $result_param) ?>

                    <section class="membres">
                        <p>Membres du site (Pseudo, Email)</p>

                        <div>

                            <?php display_membres($dbLink); ?>

                            <form action="parametre.php" method="post">
                                <input type="text" name="pseudo" placeholder="Pseudo" required/>
                                <input type="email" name="email" placeholder="Email" required/>
                                <input type="password" name="pwd" placeholder="Mot de passe" required/>
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
    //Couper la connexion avec la BDD
    mysqli_close($dbLink);

    end_page();
?>
