<?php

include_once 'assets/php/display_HTML.php';
include_once 'assets/php/mySQL.php';

// Création ou restauration de la session
session_start();

//connexion a la base de donnée
$dbLink = connect_db();

$tag = NULL;
$role = NULL;

//recuperation du nombre de message par page
$nb_max_msg = get_n_mes($dbLink);

//numero de la page
$page_number = 1;

//Si on est connecté, on récupère le rôle de l'utilisateur
if(isset($_SESSION['loggedin'])) {
    $role = get_role($dbLink, $_SESSION['user_id']);
}

//Si on a le numero de la page dans l'url, on affiche la page correspondante
if(isset($_GET['page']))
    $page_number = (int) $_GET['page'];

//Si on a le tag a à rechercher dans l'url, la variable tag récupère la valeur
if(isset($_GET['search']))
    $tag = $_GET['search'];


//entete
TopPage();

NavPage($role);

//affiche les messages
$nb_ligne = display_msg($dbLink, $tag, $page_number, $nb_max_msg, $role);

tagFooterPage($dbLink);

//Couper la connexion avec la BDD
mysqli_close($dbLink);