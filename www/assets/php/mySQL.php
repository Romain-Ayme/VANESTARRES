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

function insert_msg_db($uid, $msg, $dbLink) {

    $query = 'INSERT INTO messages (ID_USER, MESSAGE) VALUES 
                                                                    (\'' . $uid . '\',
                                                                    \'' . $msg . '\')';
    execute_query($dbLink, $query);
    return mysqli_insert_id($dbLink);
}