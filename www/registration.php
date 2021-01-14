<?php
// Création ou restauration de la session
session_start();

// Si déja connecté on fait une redirection vers : home.php
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

include_once 'assets/php/utils.inc.php';
start_page('Inscription');
