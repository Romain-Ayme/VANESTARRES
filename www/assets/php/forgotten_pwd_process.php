<?php


//envoie du mail
function send_mail($email, $dbLink): string
{

    $query = 'SELECT * FROM users WHERE EMAIL = \''. $email .'\' ';
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        $dbRow = mysqli_fetch_assoc($dbResult);

        $code = bin2hex(random_bytes(10));

        $subject = 'Mot de passe oublié';

        $message = 'Bonjour. Vous avez effectuer une demande de nouveau mot de passe.
                    Veuillez vous rendre au lien suivant: http://romain-ayme.alwaysdata.net/nouveau_mdp.php?code=' . $code . '
                    Si vous n\'êtes pas à l\'origine de cette demande, trouvez le coupable.';

        mail($email,  $subject, $message);

        $query = 'UPDATE users SET PSWD = \'' . $code . '\' WHERE EMAIL = \'' . $email . '\'';
        execute_query($dbLink, $query);
    }

    return 'Un mail contenant les instructions à suivre va vous être envoyé. Si vous ne le recevez pas, vérifiez que vous avez bien orthographié votre adresse.';
}


//on change le mot de passe
function change_pwd($pass1, $pass2, $code, $dbLink): string
{

        //Si les deux champs ne sont pas identiques
        if ($pass1 != $pass2) {

            return 'les mots de passe ne sont pas identiques';
        }

        // Si c'est bon
        else {
            $pass1 = password_hash($pass1, PASSWORD_DEFAULT);    //Chiffrage du mot de passe

            $query = 'UPDATE users SET PSWD =\'' . $pass1 . '\' WHERE PSWD = \'' . $code . '\''; //Ecriture de la requête (mise à jour du mot de passe)
            execute_query($dbLink, $query);        //Execution de la requête

            header('location: ../../login.php');        // Envoie sur login.php
        }
        return '';
}





