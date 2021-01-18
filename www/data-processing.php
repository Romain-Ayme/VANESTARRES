<?php

include_once 'assets/php/mySQL.php';

$id = $_POST['id'];
$sexe = $_POST['sexe'];
$e_mail = $_POST['e_mail'];
$mdp = $_POST['mdp'];
$check_mdp = $_POST['check_mdp'];
$tel = $_POST['tel'];
$pays = $_POST['pays'];
$cg = $_POST['cg'];
$action = $_POST['action'];

$today = date('Y-m-d');

$dbLink = OpenCon();


$query = 'INSERT INTO user (USER_ID, Civilite, E_mail, Mdp, Check_mdp, Tel, Pays, CG, Date_ajout) VALUES 
                                                                                                   (\'' . $id . '\',
                                                                                                   \'' . $sexe . '\',
                                                                                                   \'' . $e_mail . '\',
                                                                                                   \'' . $mdp . '\',
                                                                                                   \'' . $check_mdp . '\',
                                                                                                   \'' . $tel . '\',
                                                                                                   \'' . $pays . '\',
                                                                                                   \'' . $cg . '\',
                                                                                                   \'' . $today . '\')';

if(!($dbResult = mysqli_query($dbLink, $query)))
{
    echo 'Erreur de requête<br/>';

    echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';

    echo 'Requête : ' . $query . '<br/>';
    exit();
}

else {
    echo 'Bonjour, ' . $id . '<br/> Votre inscription a bien été enregistrée, merci.';
}
?>

<form action="main.php" method="post">
    <input type="submit" name="action" value="Revenir au sommaire">
</form>
