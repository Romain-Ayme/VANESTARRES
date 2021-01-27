<?php

//On change le mot de passe
function change_pwd($new_pwd, $dbLink): string
{

    //hashage pwd
    $pwd = password_hash($new_pwd, PASSWORD_DEFAULT);


    $query = 'UPDATE users SET PSWD = \'' . $pwd . '\' WHERE ID_USER = ' . $_SESSION['user_id'];
    execute_query($dbLink, $query);

    // on change le mdp de session
    $_SESSION['password'] =  $pwd;

    return 'Votre mot de passe a bien été modifié';
}


//On change les paramètres
function change_param($n_msg, $n_min, $n_max, $dbLink): string
{

    if($n_msg > 0 && $n_min <= $n_max && $n_min > 0) {

        $query = 'UPDATE parametres SET N_MSG = \'' . $n_msg . '\' , N_MIN = \'' . $n_min . '\' , N_MAX = \'' . $n_max . '\' WHERE TRUE';
        execute_query($dbLink, $query);

        return 'La modification des paramètres a bien été prise en compte';
    }

    elseif($n_msg <= 0) {
        return 'Le nombre de message à afficher doit être supérieur à 0';
    }

    elseif($n_min > $n_max) {
        return 'Le nombre minimum avant le don doit etre inférieur au nombre maximum avant le don';
    }

    elseif($n_min <= 0) {
        return 'Le nombre minimum avant le don doit etre supérieur à 0';
    }
}


//On regarde si le pseudo existe deja
function check_pseudo_modif($pseudo, $dbLink, $id_user): ?string
{

    $query = 'SELECT PSEUDO FROM users WHERE PSEUDO = \'' . $pseudo . '\' AND ID_USER !=' . $id_user;
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        return 'Le pseudo existe deja';
    }
    return NULL;
}


//On regarde si l'email existe deja
function check_e_mail_modif($e_mail, $dbLink, $id_user): ?string
{

    //Vérification de la syntaxe de l'email
    if(!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $e_mail)) {
        return 'La syntaxe de l\'email n\'est pas conforme';
    }
    else {
        $query = 'SELECT EMAIL FROM users WHERE EMAIL = \'' . $e_mail . '\' AND ID_USER !=' . $id_user;
        $dbResult = execute_query($dbLink, $query);

        if (mysqli_num_rows($dbResult) != 0) {
            return 'L\'email existe deja';
        }
    }
    return NULL;
}


//On modifie les informations sur l'utilisateur
function update_user($id_user, $pseudo, $email, $dbLink): string
{

    $result = check_pseudo_modif($pseudo, $dbLink, $id_user);
    if($result == NULL) {
        $result = check_e_mail_modif($email, $dbLink, $id_user);
    }

    if($result == NULL) {

        $query = 'UPDATE users SET PSEUDO = \'' . $pseudo . '\' , EMAIL = \'' . $email . '\' WHERE  ID_USER = ' . $id_user;
        execute_query($dbLink, $query);

        return 'Utilisateur <b>' . $pseudo . '</b> mis a jour';
    }

    return $result;
}


//On supprime l'utilisateur
function toggle_user($id_user, $dbLink): string
{

    $query = 'SELECT PSEUDO, DELETED FROM users WHERE ID_USER =' . $id_user;
    $dbResult = execute_query($dbLink, $query);
    $dbRow = mysqli_fetch_assoc($dbResult);
    $is_deleted = $dbRow['DELETED'];
    $pseudo = $dbRow['PSEUDO'];

    if($is_deleted == 'Y') {
        $is_deleted = 'N';
        $result = ' réactivé';
    }

    else {
        $is_deleted = 'Y';
        $result = ' désactivé';
    }

    $query = 'UPDATE users SET DELETED = \'' . $is_deleted . '\' WHERE  ID_USER = ' . $id_user;
    execute_query($dbLink, $query);

    return 'Utilisateur <b>' . $pseudo . '</b>' . $result;
}