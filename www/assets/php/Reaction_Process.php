<?php

// Création ou restauration de la session
session_start();

// Si on est pas connecté on fait une redirection vers : login.php
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../login.php');
    exit;
}

include_once 'mySQL.php';

//Connexion à la base de donnée
$dbLink = connect_db();

$tag = NULL;

//Si Si on a l'id d'un message ou une icone(reaction) dans l'url, on les recuperes
if(isset($_GET['id_m']) && isset($_GET['icone'])) {
    $id_m = (int) $_GET['id_m'];
    $icone = $_GET['icone'];
}

//Sinon, arrêt du programme
else {
    exit;
}


$query = 'SELECT ID_NOTE, NOTE FROM notes WHERE ID_MESSAGE = ' . $id_m . ' AND ID_USER = ' . $_SESSION['user_id'];
$dbResult = execute_query($dbLink, $query);

if(mysqli_num_rows($dbResult) != 0) {
    $dbRow = mysqli_fetch_assoc($dbResult);
    $id_note = $dbRow['ID_NOTE'];
    $old_icone = $dbRow['NOTE'];


    if($old_icone == $icone) {
        //Si l'utilisateur a cliqué sur une icone déjâ activée, on l'efface
        $query = 'DELETE FROM notes WHERE ID_NOTE = ' . $id_note;
        execute_query($dbLink, $query);
    }
    else {
        //Si l'utilisateur a choisi une autre icone que celle déjâ activée, on update la BDD
        $query = 'UPDATE notes SET NOTE = \'' . $icone . '\' WHERE ID_NOTE = ' . $id_note;
        execute_query($dbLink, $query);
    }
}
else {
    // Si il n'y a pas de note pour ce message et cet utilisateur, alors on insert la note
    $query = 'INSERT INTO notes (ID_MESSAGE, ID_USER, NOTE) VALUES (' . $id_m . ', ' . $_SESSION['user_id'] . ', \'' . $icone . '\')';
    execute_query($dbLink, $query);
}

//Si l'utilisateur a cliqué sur l'icone "love", on regarde si il faut faire un don
if($icone == 'L')
{
    $query = 'SELECT NOTE, COUNT(*) FROM notes WHERE ID_MESSAGE = ' . $id_m . ' AND NOTE = \'L\' GROUP BY NOTE';
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        $dbRow = mysqli_fetch_assoc($dbResult);
        $nb_love = $dbRow['COUNT(*)'];

        $query = 'SELECT NB_AVANT_DON FROM messages WHERE ID_MESSAGE =' . $id_m;
        $dbResult = execute_query($dbLink, $query);

        $dbRow = mysqli_fetch_assoc($dbResult);
        $nb_avant_don = $dbRow['NB_AVANT_DON'];

        if($nb_love == $nb_avant_don) {
            $query = 'UPDATE messages SET DON_USER = ' . $_SESSION['user_id'] . ' WHERE ID_MESSAGE = ' . $id_m;
            execute_query($dbLink, $query);
        }
    }
}

//Couper la connexion avec la BDD
mysqli_close($dbLink);

//On retourne sur la page d'avant
header('Location: ' . $_SERVER['HTTP_REFERER']);