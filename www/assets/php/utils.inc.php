<?php

function get_n_mes($dbLink): int
{

    $query = 'SELECT N_MSG FROM parametres WHERE TRUE';
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    return $dbRow['N_MSG'];
}


//On recupere le role de l'utilisateur connecté
function get_role($dbLink, $id_user): string
{

    $query = 'SELECT ROLE_USER FROM users WHERE ID_USER =' . $id_user;
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    return $dbRow['ROLE_USER'];
}



//On affiche si la demande à reussi, sinon, on affiche pourquoi
function display_error($post_id, $error) {
    if (isset($_POST[$post_id])) {
        echo '<div class="messages">' . PHP_EOL;
        echo '<p>' . $error . '</p>' . PHP_EOL;
        echo '</div>' . PHP_EOL;
    }
}

//On affiche les paramètres (nombre de message par page, nombre min et max avant don)
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

        '<label><b>nombre aléatoire minimum pour le don : </b>' . PHP_EOL .
        '<input type="number" name="n_max" value="' . $n_max . '" required/><br/>' . PHP_EOL .
        '</label>' . PHP_EOL .

        '<input type="submit" name="action_param" value="Sauvegarder les paramètres"/>' . PHP_EOL .

        '</form>' . PHP_EOL;

}

//On affiche tous les membres du site
function display_membres($dbLink) {

    $query = 'SELECT PSEUDO, EMAIL, ID_USER, DELETED FROM users WHERE ID_USER !=' . $_SESSION['user_id'];
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        while ($dbRow = mysqli_fetch_assoc($dbResult)) {
            $pseudo = $dbRow['PSEUDO'];
            $email = $dbRow['EMAIL'];
            $id_user = $dbRow['ID_USER'];
            $is_deleted = $dbRow['DELETED'];

            echo '<form action="parametre.php" method="post">' . PHP_EOL;

            if($is_deleted == 'Y') {

                echo '<input type="text" name="pseudo" value="' . $pseudo . '" disabled/>' . PHP_EOL .
                    '<input type="text" name="email" value="' . $email . '" disabled/>' . PHP_EOL .
                    '<input type="submit" name="action_toggle" value="Réactiver"/>' . PHP_EOL;
            }
            else {
                echo '<input type="text" name="pseudo" value="' . $pseudo . '" required/>' . PHP_EOL .
                    '<input type="text" name="email" value="' . $email . '" required/>' . PHP_EOL .
                    '<input type="submit" name="action_update" value="Modifier"/>' . PHP_EOL .
                    '<input type="submit" name="action_toggle" value="Désactiver"/>' . PHP_EOL;
            }
            echo '<input type="hidden" name="id_user" value="' . $id_user . '"/>' . PHP_EOL .
                '</form>' . PHP_EOL;
        }
    }
}



//Recherche des messages qui on le tag qu'on recherche
function search_tag($tag, $dbLink, $page_number, $nb_max_msg) {

    $query = 'SELECT ID_TAG FROM tags WHERE NOM_TAG = \'' . $tag . '\'';
    $dbResult = execute_query($dbLink, $query);

    $msg_list = NULL;

    if (mysqli_num_rows($dbResult) != 0) {
        $dbRow = mysqli_fetch_assoc($dbResult);
        $id_tag = $dbRow['ID_TAG'];

        //On recupere tous les messages qui ont le tag qu'on recherche
        $query = 'SELECT messages.ID_MESSAGE, MESSAGE, ID_USER, DATE_MESS, IMG, DONNE, DON_USER
                            FROM messages
                            JOIN messages_tags ON messages.ID_MESSAGE = messages_tags.ID_MESSAGE
                            WHERE ID_TAG = ' . $id_tag . ' ORDER BY DATE_MESS DESC LIMIT ' . ($page_number - 1) * $nb_max_msg . ' , ' . $nb_max_msg . ' ';
        $msg_list = execute_query($dbLink, $query);
    }

    return $msg_list;
}

function manage_tag($msg, $dbLink, $id_msg) {

    // Extraction des tags dans le message
    preg_match_all('/ß([\w]+)/', $msg, $tags);

    //Manage du tag
    foreach ($tags[1] as $val) {

        //Verification de l'existance du tag
        $query = 'SELECT ID_TAG FROM tags WHERE NOM_TAG = \'' . $val . '\'';
        $dbResult = execute_query($dbLink, $query);

        if (mysqli_num_rows($dbResult) == 0) {

            //Creation du tag
            $query = 'INSERT INTO tags (NOM_TAG) VALUES (\'' . $val . '\')';
            execute_query($dbLink, $query);
            $id_tag = mysqli_insert_id($dbLink);
        }

        else {
            $dbRow = mysqli_fetch_assoc($dbResult);
            $id_tag = $dbRow['ID_TAG'];
        }

        //Insertion de id_tag et id_msg dans messages_tags
        $query = 'INSERT INTO messages_tags (ID_TAG, ID_MESSAGE) VALUES                                                                     
                                                                    (\'' . $id_tag . '\',
                                                                    \'' . $id_msg . '\')';
        execute_query($dbLink, $query);
    }
}


function nommage($type): string
{
    if ($type == 'image/png') {
        return 'image.png';
    }

    elseif ($type == 'image/gif') {
        return 'image.gif';
    }

    elseif ($type == 'image/jpg') {
        return 'image.jpg';
    }

    else  {
        return 'image.jpeg';
    }
}


function save_img($id_msg): string
{

    $repertoireDestination = '../../Public/user/1/msg/' . $id_msg . '/';
    $nom_img = nommage($_FILES['img']['type']);

    if (!is_dir($repertoireDestination)) {

        //Si le repertoire n'existe pas, on le crée
        mkdir($repertoireDestination, 0777, true);
    }

    else {
        array_map('unlink', glob($repertoireDestination.'*'));
    }

    move_uploaded_file($_FILES["img"]["tmp_name"] , $repertoireDestination.$nom_img);

    return $repertoireDestination.$nom_img;
}


function delete_img($id_msg)
{

    $repertoireDestination = '../public/image_msg/' . $id_msg . '/';

    if (is_dir($repertoireDestination)) {

        //on efface l'image
        array_map('unlink', glob($repertoireDestination.'*'));

        //on efface le repertoire
        rmdir($repertoireDestination);
    }
}

function delete_linked_tag($id_msg, $dbLink) {

    $query = 'DELETE FROM messages_tags WHERE ID_MESSAGE = ' . $id_msg;
    execute_query($dbLink, $query);
}

function delete_note($id_msg, $dbLink) {
    $query = 'DELETE FROM notes WHERE ID_MESSAGE = ' . $id_msg;
    execute_query($dbLink, $query);
}


//On insert le message dans la BDD
function insert_msg_db($uid, $msg, $dbLink, $id_mess) {

    if($id_mess == NULL) {

        //On recupere le min et max avant don
        $query = 'SELECT * FROM parametres';
        $dbResult = execute_query($dbLink, $query);

        $dbRow = mysqli_fetch_assoc($dbResult);
        $n_min = $dbRow['N_MIN'];
        $n_max = $dbRow['N_MAX'];

        //On choisi un nombre avant don entre min et max
        $nb_avant_don = rand($n_max, $n_min);

        $query = 'INSERT INTO messages (ID_USER, MESSAGE, NB_AVANT_DON) VALUES 
                                                                            (\'' . $uid . '\',
                                                                            \'' . $msg . '\',
                                                                            \'' . $nb_avant_don . '\')';
        execute_query($dbLink, $query);
        return mysqli_insert_id($dbLink);
    }

    else {
        $query = 'UPDATE messages SET MESSAGE = \'' . $msg . '\' WHERE ID_MESSAGE = ' . $id_mess;
        execute_query($dbLink, $query);
        return $id_mess;
    }
}

function update_img_db ($id_mess, $img_path, $dbLink) {

    $query = 'UPDATE messages SET IMG = \'' . $img_path . '\' WHERE ID_MESSAGE = ' . $id_mess;
    execute_query($dbLink, $query);
}