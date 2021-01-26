<?php

    include_once "utils.inc.php";

    //On verifie si le pseudo exise deja pour l'inscription
    function check_pseudo($pseudo, $dbLink): ?string
    {

        $query = 'SELECT PSEUDO FROM users WHERE PSEUDO = \'' . $pseudo . '\'';
        $dbResult = execute_query($dbLink, $query);

        if (mysqli_num_rows($dbResult) != 0) {
            return 'Le pseudo existe deja';
        }
        return NULL;
    }


    //On verifie si l'email existe deja pour l'inscription
    function check_e_mail($e_mail, $dbLink): ?string
    {

        //Vérification de la syntaxe de l'email
        if(!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $e_mail)) {
            return 'La syntaxe de l\'email n\'est pas conforme';
        }
        else {
            $query = 'SELECT EMAIL FROM users WHERE EMAIL = \'' . $e_mail . '\'';
            $dbResult = execute_query($dbLink, $query);

            if (mysqli_num_rows($dbResult) != 0) {
                return 'L\'email existe deja';
            }
        }
        return NULL;
    }


    //On s'occupe d'inscrire la personne dans la BDD
    function inscription($pseudo, $e_mail, $pwd, $dbLink): string
    {

        $result = check_pseudo($pseudo, $dbLink);
        if($result == NULL) {
            $result = check_e_mail($e_mail, $dbLink);
        }

        if($result == NULL) {

            //hashage pwd
            $pwd = password_hash($pwd, PASSWORD_DEFAULT);

            $query = 'INSERT INTO users (EMAIL, PSWD, PSEUDO) VALUES 
                                                                (\'' . $e_mail . '\',
                                                                \'' . $pwd . '\',
                                                                \'' . $pseudo . '\')';
            execute_query($dbLink, $query);

            $result = 'Votre inscription à bien été prise en compte : ';
            $result .= '<a href="login.php">Se connecter</a>';

            return $result;
        }

        return $result;
    }