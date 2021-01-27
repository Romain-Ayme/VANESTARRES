<?php

function connect_db() {
    $dbLink = mysqli_connect('mysql-romain-ayme.alwaysdata.net', '223609_php', 'zK7dQm4H3')
    or die('Erreur de connexion au serveur : ' . mysqli_connect_error());
    mysqli_select_db($dbLink, 'romain-ayme_vanestarre')
    or die('Erreur dans la sélection de la base : ' . mysqli_error($dbLink));
    return $dbLink;
}

function execute_query($dbLink, $query) {
    if(!($dbResult = mysqli_query($dbLink, $query))) {
        echo 'Erreur de requête<br/>';
        echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
        echo 'Requête : ' . $query . '<br/>';
        exit();
    }
    return $dbResult;
}

function search_tag($tag, $dbLink) {

    $query = 'SELECT ID_TAG FROM tags WHERE NOM_TAG = \'' . $tag . '\'';
    $dbResult = execute_query($dbLink, $query);

    $msg_list = NULL;

    if (mysqli_num_rows($dbResult) != 0) {
        $dbRow = mysqli_fetch_assoc($dbResult);
        $id_tag = $dbRow['ID_TAG'];

        $query = 'SELECT MESSAGE, ID_USER, NB_LOVE, NB_CUTE, NB_STYLE, NB_SWAG, DATE_MESS
						FROM messages
						JOIN messages_tags ON messages.ID_MESSAGE = messages_tags.ID_MESSAGE
						WHERE ID_TAG = \'' . $id_tag . '\' ORDER BY DATE_MESS DESC';
        $msg_list = execute_query($dbLink, $query);
    }

    return $msg_list;
}

function insert_msg_db($uid, $msg, $img, $dbLink) {

    $query = 'INSERT INTO messages (ID_USER, MESSAGE, IMG) VALUES 
                                                                    (\'' . $uid . '\',
                                                                    \'' . $msg . '\',
                                                                    \'' . $img . '\')';
    execute_query($dbLink, $query);
    return mysqli_insert_id($dbLink);
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