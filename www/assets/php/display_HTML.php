<?php

//head de page
function topPage($css='css.css') {
    echo '<!DOCTYPE html>' . PHP_EOL;
    echo '<html lang="fr">' . PHP_EOL;
    echo '<head>' . PHP_EOL;
    echo "\t" . '<meta charset="UTF-8">' . PHP_EOL;
    echo "\t" . '<title>Vanestarre</title>' . PHP_EOL;
    echo "\t" . '<link rel="icon" type="image/png" href="assets/img/logo/VANESTARRE.png"/>' . PHP_EOL;
    echo "\t" . '<link rel="stylesheet" type="text/css" href="assets/css/' . $css . '">' . PHP_EOL;
    echo "\t" . '<link rel="stylesheet" type="text/css" href="assets/css/scrollUp.css">' . PHP_EOL;
    echo "\t" . '<script src="assets/js/js.js"></script>' . PHP_EOL;
    echo '</head>' . PHP_EOL;
    echo '<body>' . PHP_EOL;
}


//affiche les boutons correspondant
function sessionPage($role) {
    if (isset($_SESSION['loggedin'])) {
        echo "\t\t\t" . '<a href="settings.php"><i class="fa fa-cog"></i>Paramètres</a>' . PHP_EOL;
        echo "\t\t\t" . '<a href="assets/php/Logout_Process.php"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>' . PHP_EOL;
        if ($role == 'SUPER') {
            echo "\t\t\t" . '<a href="add_Message.php"><i class="fas fa-comment"></i>Ecrire un message</a>' . PHP_EOL;
        }
    }
    else {
        echo "\t\t\t" . '<a href="login.php"><i class="fas fa-sign-in-alt"></i>Se connecter</a>' . PHP_EOL;
        echo "\t\t\t" . '<a href="register.php"><i class="fa fa-user-plus"></i>S\'inscrire</a>' . PHP_EOL;
    }
}


//affiche la navpage (à gauche du site)
function navPage($role) {
    echo "\t" . '<nav class="nav">' . PHP_EOL;
    echo "\t\t" . '<img class="logo" alt="VANESTARRE" src="assets/img/logo/LOGOVANESTARRE.png"/>' . PHP_EOL;
    echo "\t\t" . '<div class="menu">' . PHP_EOL;
    echo "\t\t\t" . '<a href="index.php" class="home"><i class="fa fa-home"></i>Accueil</a>' . PHP_EOL;

    SessionPage($role);

    echo "\t\t" . '</div>' . PHP_EOL;
    echo "\t" . '</nav>' . PHP_EOL;
}


//On affiche le nombre de chaque réaction qu'un message a obtenu
function reactions($id_mess, $dbLink) {

    $query = 'SELECT NOTE, COUNT(*) FROM notes WHERE ID_MESSAGE = \'' . $id_mess . '\' GROUP BY NOTE';
    $dbResult = execute_query($dbLink, $query);

    $nb_love = 0;
    $nb_cute = 0;
    $nb_swag = 0;
    $nb_style = 0;

    $note = '';

    //Si il y a une note, on affiche le montant
    if (mysqli_num_rows($dbResult) != 0) {
        while($dbRow = mysqli_fetch_assoc($dbResult)) {
            if($dbRow['NOTE'] == 'L') $nb_love = $dbRow['COUNT(*)'];
            if($dbRow['NOTE'] == 'C') $nb_cute = $dbRow['COUNT(*)'];
            if($dbRow['NOTE'] == 'S') $nb_swag = $dbRow['COUNT(*)'];
            if($dbRow['NOTE'] == 'T') $nb_style = $dbRow['COUNT(*)'];
        }

        if(isset($_SESSION['loggedin'])) {
            $query = 'SELECT NOTE FROM notes WHERE ID_USER = \'' . $_SESSION['user_id'] . '\' AND ID_MESSAGE = \'' . $id_mess . '\' GROUP BY NOTE';
            $dbResult = execute_query($dbLink, $query);

            if(mysqli_num_rows($dbResult) != 0) {
                $dbRow = mysqli_fetch_assoc($dbResult);
                $note = $dbRow['NOTE'];
            }
        }
    }

    echo "\t\t\t" . '<div class="list_reaction">' . PHP_EOL;

    echo "\t\t\t\t" . '<div class ="icone_';
    if($note == 'L') echo 'on">'; else echo 'off">';
    echo '<a class="reaction love" href="assets/php/Reaction_Process.php?id_m='. $id_mess . '&icone=L"> <img class="img_reaction" alt="love" src="assets/img/icon/love.png"></a>' . $nb_love . '</div>' . PHP_EOL;

    echo "\t\t\t\t" . '<div class ="icone_';
    if($note == 'C') echo 'on">'; else echo 'off">';
    echo '<a class="reaction cute" href="assets/php/Reaction_Process.php?id_m='. $id_mess . '&icone=C"> <img class="img_reaction" alt="cute" src="assets/img/icon/cute.png"></a>' . $nb_cute . '</div>' . PHP_EOL;;

    echo "\t\t\t\t" . '<div class ="icone_';
    if($note == 'S') echo 'on">'; else echo 'off">';
    echo '<a class="reaction swag" href="assets/php/Reaction_Process.php?id_m='. $id_mess . '&icone=S"> <img class="img_reaction" alt="swag" src="assets/img/icon/swag.png"></a>' . $nb_swag . '</div>' . PHP_EOL;

    echo "\t\t\t\t" . '<div class ="icone_';
    if($note == 'T') echo 'on">'; else echo 'off">';
    echo '<a class="reaction style" href="assets/php/Reaction_Process.php?id_m='. $id_mess . '&icone=T"> <img class="img_reaction" alt="style" src="assets/img/icon/style.png"></a>' . $nb_style . '</div>' . PHP_EOL;
    echo "\t\t\t" . '</div>' , PHP_EOL;
}


//On affiche le numero de la page et les boutons pour pouvoir changer de page
function pagination ($tag, $page_number, $nb_ligne, $nb_max_msg) {
    echo "\t\t" . '<div class="pagination">' . PHP_EOL;
    echo "\t\t\t" . '<p class="paginationindex">';
    if($page_number > 1)
        echo "\t\t\t" . '<a href="index.php?search=' . $tag . '&page=' . ($page_number - 1) . '"><</a>';

    echo $page_number;

    if(!($nb_ligne ==0 || $nb_ligne < $nb_max_msg))
        echo '<a href="index.php?search=' . $tag . '&page=' . ($page_number + 1) . '">></a>';
    echo '</p>';
    echo PHP_EOL . "\t\t" . '</div>' . PHP_EOL;
}


//On affiche les messages
function display_msg($dbLink, $tag, $page_number, $nb_max_msg, $role): int
{

    //Si on recherche un tag, on execute la fonction qui va rechercher tous les messages qui sont associés au tag
    if ($tag != NULL) {
        $dbResult = search_tag($tag, $dbLink, $page_number, $nb_max_msg);
    }

    //Sinon, on recupere tous les messages de la BDD (le nombre de message qu'on recupere depend du nombre de message qu'on affiche par page)
    else {
        $query = 'SELECT ID_MESSAGE, MESSAGE, ID_USER, DATE_MESS, IMG, DONNE, DON_USER FROM messages ORDER BY DATE_MESS DESC LIMIT ' . ($page_number - 1) * $nb_max_msg . ' , ' . $nb_max_msg;
        $dbResult = execute_query($dbLink, $query);
    }

    $nb_ligne = 0;

    echo "\t" . '<div class="div_messages">' . PHP_EOL;
    echo "\t\t" . '<h1 class="titre">Dernier messages</h1>' . PHP_EOL;

    while ($dbRow = mysqli_fetch_assoc($dbResult)) {

        //au moins une ligne à afficher
        $nb_ligne = $nb_ligne + 1;

        $querynom = 'SELECT PSEUDO FROM users WHERE ID_USER = ' . $dbRow['ID_USER'];
        $dbResultNom = execute_query($dbLink, $querynom);

        $dbRowNom = mysqli_fetch_assoc($dbResultNom);

        echo "\t\t" . '<div class="message">' . PHP_EOL;
        echo "\t\t\t" . '<h2 class="author">' . $dbRowNom['PSEUDO'] . '</h2>' . PHP_EOL;

        //Si il y a une image, on l'affiche
        if($dbRow['IMG'] != NULL) {
            echo "\t\t\t" . '<div class="message_img_box"><img class="image" alt="" src="' . $dbRow['IMG'] . '"/></div>' . PHP_EOL;
        }

        echo "\t\t\t" . '<div class="message_text"><p>' . $dbRow['MESSAGE'] . '</p></div>' . PHP_EOL;

        //On affiche les icones(reactions)
        reactions($dbRow['ID_MESSAGE'], $dbLink);

        echo "\t\t\t" . '<div class="date_mess">' . $dbRow['DATE_MESS'] . '</div>' . PHP_EOL;

        //Si l'utilisateur actuel est administrateur, on affiche le bouton modifier et supprimer
        if($role == 'SUPER') {
            echo '<div class="bouton_modif">'. PHP_EOL .
                '<form action="add_Message.php" method="post">'. PHP_EOL .
                '<input type="hidden" name="id_m" value="' . $dbRow['ID_MESSAGE'] . '"/>'. PHP_EOL .
                '<input type="submit" name="modifier" value="Modifier"/>'. PHP_EOL .
                '<input type="submit" name="supprimer" value="Supprimer"/>'. PHP_EOL .
                '</form>'. PHP_EOL .
                '</div>'. PHP_EOL;
        }
        echo "\t\t" . '</div>' . PHP_EOL;

        //Si il y a un utilisateur connecté, et qu'il faut donner un don, alors on affiche un message d'alerte
        if(isset($_SESSION['loggedin'])) {
            if($dbRow['DONNE'] == 'N') {
                if ($dbRow['DON_USER'] == $_SESSION['user_id']) {
                    $query = 'UPDATE messages SET DONNE = \'Y\' WHERE ID_MESSAGE = ' . $dbRow['ID_MESSAGE'];
                    execute_query($dbLink, $query);

                    echo '<script> alert("Félicitation ! vous devez donner 10 bitcoins à Vanéstarre !") </script>';
                }
            }
        }
    }

    //Si il n'y a plus de message à afficher, on previent l'utilisateur
    if($nb_ligne == 0) {
        echo '<div class="message">' , PHP_EOL;
        echo 'Fin des messages', PHP_EOL;
        echo '</div>' , PHP_EOL;
    }

    //affiche la pagination
    pagination($tag, $page_number, $nb_ligne, $nb_max_msg);
    echo "\t" . '</div>' . PHP_EOL;

    return $nb_ligne;
}


//affiche la partie recherche de tag
function start_TagFooterPage() {
    echo "\t" . '<div class="tag_and_footer">' . PHP_EOL;
    echo "\t\t" . '<div class="tag">' . PHP_EOL;
    echo "\t\t\t" . '<div class="recherche">' . PHP_EOL;
    echo "\t\t\t\t" . '<form action="index.php" method="get">' . PHP_EOL;
    echo "\t\t\t\t\t" . '<input type="search" placeholder="Recherche par ß" name="search">' . PHP_EOL;
    echo "\t\t\t\t\t" . '<input type="submit" value="Rechercher">' . PHP_EOL;
    echo "\t\t\t\t" . '</form>' . PHP_EOL;
    echo "\t\t\t" . '</div>' . PHP_EOL;
    echo "\t\t\t" . '<div class="tendance">' . PHP_EOL;
    echo "\t\t\t\t" . '<h3>Top 10 Tendance</h3>' . PHP_EOL;
    echo "\t\t\t\t" . '<ol class="list_tendance">' . PHP_EOL;
}


//affiche le top 10 des tags les plus populaires
function tagPage($dbLink) {

    $query = 'SELECT NOM_TAG FROM messages_tags, tags WHERE messages_tags.ID_TAG = tags.ID_TAG GROUP BY messages_tags.ID_TAG ORDER BY count(*) DESC LIMIT 10';
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        while($dbRow = mysqli_fetch_assoc($dbResult)) {
            $nom_tag = $dbRow['NOM_TAG'];
            echo "\t\t\t\t\t" . '<li><a href="index.php?search='. $nom_tag . '">ß'. $nom_tag . '</a></li>' . PHP_EOL;
        }

    }
}


//affiche le footer
function end_TagFooterPage(){
    echo "\t\t\t\t" . '</ol>' . PHP_EOL;
    echo "\t\t\t" . '</div>' . PHP_EOL;
    echo "\t\t" . '</div>' . PHP_EOL;
    echo "\t\t" . '<footer class="footer">' . PHP_EOL;
    echo "\t\t\t" . '<span>Conditions d’utilisation</span>' . PHP_EOL;
    echo "\t\t\t" . '<span>Politique de Confidentialité</span>' . PHP_EOL;
    echo "\t\t\t" . '<span>Politique relative aux cookies</span>' . PHP_EOL;
    echo "\t\t\t" . '<span>Informations sur les publicités</span>' . PHP_EOL;
    echo "\t\t\t" . '<span>Plus</span>' . PHP_EOL;
    echo "\t\t\t" . '<span> 2021 VANESTARRE, Inc.</span>' . PHP_EOL;
    echo "\t\t" . '</footer>' . PHP_EOL;
    echo "\t" . '</div>' . PHP_EOL;
    echo "\t" . '<a href="#" id="scrollUp" class="invisible"></a>' . PHP_EOL;
    echo '</body>' . PHP_EOL;
    echo '</html>' . PHP_EOL;
}


//affiche l'ensemble de la partie de gauche
function tagFooterPage($dbLink) {
    start_TagFooterPage();
    tagPage($dbLink);
    end_TagFooterPage();
}


//On affiche si la demande à reussi, sinon, on affiche pourquoi
function display_error($post_id, $error) {
    if (isset($_POST[$post_id])) {
        echo '<div class="param">' . PHP_EOL;
        echo '<p>' . $error . '</p>' . PHP_EOL;
        echo '</div>' . PHP_EOL;
    }
}


//On affiche les paramètres (nombre de message par page, nombre min et max de love avant don)
function display_param($dbLink) {

    $query = 'SELECT * FROM parametres';
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    $n_msg = $dbRow['N_MSG'];
    $n_min = $dbRow['N_MIN'];
    $n_max = $dbRow['N_MAX'];

    echo '<form action="settings.php" method="post">' . PHP_EOL .

        '<label><b>Nombre de message par page : </b>' . PHP_EOL .
        '<input type="number" name="n_msg" value="' . $n_msg . '" required/><br/>' . PHP_EOL .
        '</label>' . PHP_EOL .

        '<label><b>nombre aléatoire minimum pour le don : </b>' . PHP_EOL .
        '<input type="number" name="n_min" value="' . $n_min . '" required/><br/>' . PHP_EOL .
        '</label>' . PHP_EOL .

        '<label><b>nombre aléatoire maximum pour le don : </b>' . PHP_EOL .
        '<input type="number" name="n_max" value="' . $n_max . '" required/><br/>' . PHP_EOL .
        '</label>' . PHP_EOL .

        '<input type="submit" name="action_param" value="Sauvegarder les paramètres"/>' . PHP_EOL .

        '</form>' . PHP_EOL;

}


//On affiche tous les membres du site dans les parametres
function display_membres($dbLink) {

    $query = 'SELECT PSEUDO, EMAIL, ID_USER, DELETED FROM users WHERE ID_USER !=' . $_SESSION['user_id'];
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        while ($dbRow = mysqli_fetch_assoc($dbResult)) {
            $pseudo = $dbRow['PSEUDO'];
            $email = $dbRow['EMAIL'];
            $id_user = $dbRow['ID_USER'];
            $is_deleted = $dbRow['DELETED'];

            echo '<form action="settings.php" method="post">' . PHP_EOL;

            if($is_deleted == 'Y') {

                echo '<input type="text" name="pseudo" value="' . $pseudo . '" disabled/>' . PHP_EOL .
                    '<input type="email" name="email" value="' . $email . '" disabled/>' . PHP_EOL .
                    '<input type="submit" name="action_toggle" value="Réactiver"/>' . PHP_EOL;
            }
            else {
                echo '<input type="text" name="pseudo" value="' . $pseudo . '" required/>' . PHP_EOL .
                    '<input type="email" name="email" value="' . $email . '" required/>' . PHP_EOL .
                    '<input type="submit" name="action_update" value="Modifier"/>' . PHP_EOL .
                    '<input type="submit" name="action_toggle" value="Désactiver"/>' . PHP_EOL;
            }
            echo '<input type="hidden" name="id_user" value="' . $id_user . '"/>' . PHP_EOL .
                '</form>' . PHP_EOL;
        }
    }
}
