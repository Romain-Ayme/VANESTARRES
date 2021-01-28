<?php

//connexion a la bdd
function connect_db() {
    $dbLink = mysqli_connect('mysql-romain-ayme.alwaysdata.net', '223609_php', 'zK7dQm4H3')
    or die('Erreur de connexion au serveur : ' . mysqli_connect_error());
    mysqli_select_db($dbLink, 'romain-ayme_vanestarre')
    or die('Erreur dans la sélection de la base : ' . mysqli_error($dbLink));
    return $dbLink;
}


//execution de la requete
function execute_query($dbLink, $query) {
    if(!($dbResult = mysqli_query($dbLink, $query))) {
        echo 'Erreur de requête<br/>';
        echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
        echo 'Requête : ' . $query . '<br/>';
        exit();
    }
    return $dbResult;
}


//on recupere le nombre de message par page à afficher
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


//on change le path de l'image dans la bdd
function update_img_db ($id_mess, $img_path, $dbLink) {

    $query = 'UPDATE messages SET IMG = \'' . $img_path . '\' WHERE ID_MESSAGE = ' . $id_mess;
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


//on supprime les notes
function delete_note($id_msg, $dbLink) {
    $query = 'DELETE FROM notes WHERE ID_MESSAGE = ' . $id_msg;
    execute_query($dbLink, $query);
}