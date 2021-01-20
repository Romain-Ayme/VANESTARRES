<?php

    include_once "assets/php/utils.inc.php";



    // Création ou restauration de la session
    session_start();

    // Si on est pas connecté on fait une redirection vers : login.php
    if (!isset($_SESSION['loggedin'])) {
        header('Location: login.php');
        exit;
    }

    $dbLink = connect_db();

    //    $msg = $_POST['msg'];
    //    $img = $_POST['img'];

    $msg = 'salutation ! ßcoucou ßcovid_19 comment ca va ? ßcanard';
    $img = NULL;

    $id_msg = insert_msg_db($_SESSION['user_id'], $msg, $img, $dbLink);

    manage_tag($msg, $dbLink, $id_msg);

    header('Location: index.php');
