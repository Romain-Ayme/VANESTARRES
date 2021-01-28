<?php

include_once "image_process.php";
include_once "tag_process.php";
include_once "mySQL.php";

// Création ou restauration de la session
session_start();

// Si on est pas connecté on fait une redirection vers : login.php
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

//Connexion à la base de donnée
$dbLink = connect_db();

//Modification du message
if($_POST['id_m'] != NULL) {

    //On recupere le message
    $msg =  filter_input(INPUT_POST, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);

    $id_msg = $_POST['id_m'];

    //On modifie le message dans la BDD
    $id_msg = insert_msg_db($_SESSION['user_id'], $msg, $dbLink, $id_msg);

    delete_linked_tag($id_msg, $dbLink);
}

//creation du message
else {
    $id_msg = NULL;

    //On recupere le message
    $msg = filter_input(INPUT_POST, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);

    //On insert le message dans la BDD
    $id_msg = insert_msg_db($_SESSION['user_id'], $msg, $dbLink, $id_msg);

}

//0n s'occupe de la partie des tags
manage_tag($msg, $dbLink, $id_msg);

if($_FILES['img']['size'] != 0){

    $img_path = save_img($id_msg);

    update_img_db($id_msg, $img_path, $dbLink);
}

//Couper la connexion avec la BDD
mysqli_close($dbLink);

//On retourne sur index.php
header('Location: ../../index.php');
