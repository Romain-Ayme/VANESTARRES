<?php
// Création ou restauration de la session
session_start();

include_once 'assets/php/utils.inc.php';
include_once 'assets/php/HTML.php';
include_once 'assets/php/mySQL.php';

$dbLink = connect_db();
$tag = NULL;
$role = NULL;

$nb_max_msg = get_n_mes($dbLink);
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

TopPage();
NavPage();
$nb_ligne = display_msg($dbLink, $tag, $page_number, $nb_max_msg, $role);
tagFooterPage($dbLink);