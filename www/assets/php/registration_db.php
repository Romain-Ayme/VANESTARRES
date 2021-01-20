<?php
    include_once "utils.inc.php";

    function check_pseudo($pseudo, $dbLink) {

        $query = 'SELECT PSEUDO FROM users WHERE PSEUDO = \'' . $pseudo . '\'';
        $dbResult = execute_query($dbLink, $query);

        if (mysqli_num_rows($dbResult) != 0) {
            return 'Le pseudo existe deja';
        }
        return NULL;
    }

    function check_e_mail($e_mail, $dbLink) {

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

    function inscription($pseudo, $e_mail, $pwd, $dbLink) {

        $result = check_pseudo($pseudo, $dbLink);
        if($result == NULL) {
            $result = check_e_mail($e_mail, $dbLink);
        }

        if($result == NULL) {

            //hashage pwd
            $pwd = md5($pwd);

            $query = 'INSERT INTO users (EMAIL, PSWD, PSEUDO) VALUES 
                                                                (\'' . $e_mail . '\',
                                                                \'' . $pwd . '\',
                                                                \'' . $pseudo . '\')';
            execute_query($dbLink, $query);

            $result = 'Votre inscription à bien était prise en compte : ';
            $result .= '<a href="login.php">Se connecter</a>';

            return $result;
        }

        return $result;
    }