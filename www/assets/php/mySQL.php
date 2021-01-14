<?php
function OpenCon() {
    $dbhost = 'mysql-romain-ayme.alwaysdata.net';
    $dbuser = '223609_php';
    $dbpass = 'zK7dQm4H3';
    $db = 'romain-ayme_vanestarre';
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die ('Connect failed: %s\n'. $conn -> error);

    return $conn;
}

function OpenConlocal() {
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $db = 'vanestarre';
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die ('Connect failed: %s\n'. $conn -> error);

    return $conn;
}

function OpenConPerso($dbhost, $dbuser, $dbpass, $db) {
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die ('Connect failed: %s\n'. $conn -> error);

    return $conn;
}

function CloseCon($conn) {
    $conn -> close();
}

function DisplayQuery($result, $t) {
    while($rows=mysqli_fetch_array($result)){
        echo $rows[$t].'<br/>';
    }
}
