<?php

function login_process($email, $password, $dbLink): string
{

    $query = 'SELECT PSWD, DELETED FROM users WHERE EMAIL =\'' . $email . '\'';
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        $db_row = mysqli_fetch_assoc($dbResult);
        $pwd = $db_row['PSWD'];
        $is_deleted = $db_row['DELETED'];

        if (password_verify($password, $pwd)) {

            if($is_deleted == 'N')
            {
                session_start();

                $query = 'SELECT * FROM users WHERE EMAIL = \'' . $_POST['email'] . '\'';
                $db_result = execute_query($dbLink, $query);
                $db_row = mysqli_fetch_assoc($db_result);
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['pseudo'] = $db_row['PSEUDO'];
                $_SESSION['email'] = $db_row['EMAIL'];
                $_SESSION['user_id'] = $db_row['ID_USER'];
                $_SESSION['password'] = $db_row['PSWD'];

                header('Location: index.php');
            }

            else
                return 'Le compte a été désactivé';

        }

        else {
            return 'Login ou mot de passe incorrect';
        }

    }

    else {
        return 'Login ou mot de passe incorrect';
    }

}