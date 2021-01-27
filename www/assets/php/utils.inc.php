<?php

function get_n_mes($dbLink): int
{

    $query = 'SELECT N_MSG FROM parametres WHERE TRUE';
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    return $dbRow['N_MSG'];
}


//On recupere le role de l'utilisateur connecté
function get_role($dbLink, $id_user): string
{

    $query = 'SELECT ROLE_USER FROM users WHERE ID_USER =' . $id_user;
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    return $dbRow['ROLE_USER'];
}


function tendance($dbLink) {

    $query = 'SELECT NOM_TAG FROM messages_tags, tags WHERE messages_tags.ID_TAG = tags.ID_TAG GROUP BY messages_tags.ID_TAG ORDER BY count(*) DESC LIMIT 10';
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        while($dbRow = mysqli_fetch_assoc($dbResult)) {
            $nom_tag = $dbRow['NOM_TAG'];
            echo '<a href="index.php?search='. $nom_tag . '">ß'. $nom_tag . '</a><br/>' . PHP_EOL;
        }

    }
}

//On affiche si la demande à reussi, sinon, on affiche pourquoi
function display_error($post_id, $error) {
    if (isset($_POST[$post_id])) {
        echo '<section>' . PHP_EOL;
        echo '<p>' . $error . '</p>' . PHP_EOL;
        echo '</section>' . PHP_EOL;
    }
}

//On affiche les paramètres (nombre de message par page, nombre min et max avant don)
function display_param($dbLink) {

    $query = 'SELECT * FROM parametres';
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    $n_msg = $dbRow['N_MSG'];
    $n_min = $dbRow['N_MIN'];
    $n_max = $dbRow['N_MAX'];

    echo '<form action="settings.php" method="post">' . PHP_EOL .

        '<label><b>Nombre de message par page : </b>' . PHP_EOL .
        '<input type="number" name="n_msg" value="' . $n_msg . '" required/><br/>' . PHP_EOL .
        '</label>' . PHP_EOL .

        '<label><b>nombre aléatoire minimum pour le don : </b>' . PHP_EOL .
        '<input type="number" name="n_min" value="' . $n_min . '" required/><br/>' . PHP_EOL .
        '</label>' . PHP_EOL .

        '<label><b>nombre aléatoire minimum pour le don : </b>' . PHP_EOL .
        '<input type="number" name="n_max" value="' . $n_max . '" required/><br/>' . PHP_EOL .
        '</label>' . PHP_EOL .

        '<input type="submit" name="action_param" value="Sauvegarder les paramètres"/>' . PHP_EOL .

        '</form>' . PHP_EOL;

}

//On affiche tous les membres du site
function display_membres($dbLink) {

    $query = 'SELECT PSEUDO, EMAIL, ID_USER FROM users WHERE ID_USER !=' . $_SESSION['user_id'];
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        while ($dbRow = mysqli_fetch_assoc($dbResult)) {
            $pseudo = $dbRow['PSEUDO'];
            $email = $dbRow['EMAIL'];
            $id_user = $dbRow['ID_USER'];

            echo '<form action="settings.php" method="post">' . PHP_EOL .
                '<input type="text" name="pseudo" value="' . $pseudo . '" required/>' . PHP_EOL .
                '<input type="text" name="email" value="' . $email . '" required/>' . PHP_EOL .
                '<input type="submit" name="action_update" value="Modifier"/>' . PHP_EOL .
                '<input type="submit" name="action_delete" value="Supprimer"/>' . PHP_EOL .
                '<input type="hidden" name="id_user" value="' . $id_user . '"/>' . PHP_EOL .
                '</form>' . PHP_EOL;
        }
    }
}
