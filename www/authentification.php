<?php
session_start();
include_once 'assets/php/LoginAuth.php';
include_once 'assets/php/mySQL.php';

$pdo = new PDO('mysql:dbname=romain-ayme_vanestarre;host=mysql-romain-ayme.alwaysdata.net', '223609_php', 'zK7dQm4H3');

$userService = new UserService($pdo, $_POST['email'], $_POST['password']);
if ($user_id = $userService->login()) {
    //  (tag 5)
    $_SESSION['name'] = $userService->getUser()['EMAIL'];

    echo 'Logged it as user id: '.$user_id;
    $userData = $userService->getUser();
    // do stuff
    header('Location: home.php');
} else {
    echo 'Invalid login';
    header('Location: fail.php');
}

