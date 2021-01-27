<?php
// Création ou restauration de la session
session_start();
// Suppression de la session
session_destroy();
// Redirection vers la page : index.php
header('Location: ../../index.php');
