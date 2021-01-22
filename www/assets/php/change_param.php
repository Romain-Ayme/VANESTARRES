<?php

function change_pwd($new_pwd, $dbLink): string
{

    //hashage pwd
    $pwd = md5($new_pwd);

    $query = 'UPDATE users SET PSWD = \'' . $pwd . '\' WHERE ID_USER = ' . $_SESSION['user_id'];
    execute_query($dbLink, $query);

    return 'Votre mot de passe a bien été modifié';
}

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

function check_pseudo_modif($pseudo, $dbLink, $id_user): ?string
{

    $query = 'SELECT PSEUDO FROM users WHERE PSEUDO = \'' . $pseudo . '\' AND ID_USER !=' . $id_user;
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        return 'Le pseudo existe deja';
    }
    return NULL;
}

function check_e_mail_modif($e_mail, $dbLink, $id_user): ?string
{

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

function delete_user($pseudo, $id_user, $dbLink) {

    $query = 'DELETE FROM users WHERE ID_USER = ' . $id_user;
    execute_query($dbLink, $query);

    return 'Utilisateur <b>' . $pseudo . '</b> supprimé';
}