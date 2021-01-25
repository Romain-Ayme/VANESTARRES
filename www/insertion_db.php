<?php

    include_once "assets/php/utils.inc.php";

    // Création ou restauration de la session
    session_start();

    // Si on est pas connecté on fait une redirection vers : login.php
    if (!isset($_SESSION['loggedin'])) {
        header('Location: login.php');
        exit;
    }

    //Connexion à la base de donnée
    $dbLink = connect_db();

    //On recupere le message et l'image
    //    $msg = $_POST['msg'];
    //    $img = $_POST['img'];


    //test-----------------------------------
    $msg = 'salutation ! ßcoucou ßcovid_19 comment ca va ? ßcanard';
    $img = NULL;

    //On insert le message dans la BDD
    $id_msg = insert_msg_db($_SESSION['user_id'], $msg, $img, $dbLink);

    //0n s'occupe de la partie des tags
    manage_tag($msg, $dbLink, $id_msg);

    //Couper la connexion avec la BDD
    mysqli_close($dbLink);

    //On retourne sur index.php
    header('Location: index.php');
