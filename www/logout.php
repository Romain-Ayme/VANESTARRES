<?php
// Création ou restauration de la session
session_start();
// Suppression de la session
session_destroy();
// Redirection vers la page : login.php
header('Location: index.php');