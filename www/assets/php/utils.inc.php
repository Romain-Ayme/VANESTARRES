<?php
function start_page($title)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">
        <head>
            <meta charset="utf-8"/>
            <title><?php echo $title; ?></title>
            <link rel="icon" type="image/png" href="assets/Images/VANESTARRE.png" />
            <link href="assets/css/style.css" rel="stylesheet" type="text/css">
            <link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
            <script type="text/javascript" src="assets/js/js.js"></script>
        </head>
        <body>
    <?php
}


function end_page()
{
    ?>
        </body>
    </html>
    <?php
}

function connect_db()
{
    $dbLink = mysqli_connect('mysql-romain-ayme.alwaysdata.net', '223609_php', 'zK7dQm4H3')
    or die('Erreur de connexion au serveur : ' . mysqli_connect_error());
    mysqli_select_db($dbLink, 'romain-ayme_vanestarre')
    or die('Erreur dans la sélection de la base : ' . mysqli_error($dbLink));
    return $dbLink;
}

function execute_query($dbLink, $query) {
    if(!($dbResult = mysqli_query($dbLink, $query)))
    {
        echo 'Erreur de requête<br/>';
        echo 'Erreur : ' . mysqli_error($dbLink) . '<br/>';
        echo 'Requête : ' . $query . '<br/>';
        exit();
    }
    return $dbResult;
}

function search_tag($tag, $dbLink, $page_number, $nb_max_msg) {

    $query = 'SELECT ID_TAG FROM tags WHERE NOM_TAG = \'' . $tag . '\'';
    $dbResult = execute_query($dbLink, $query);

    $msg_list = NULL;

    if (mysqli_num_rows($dbResult) != 0) {
        $dbRow = mysqli_fetch_assoc($dbResult);
        $id_tag = $dbRow['ID_TAG'];

        $query = 'SELECT MESSAGE, ID_USER, DATE_MESS, NB_AVANT_DON
						FROM messages
						JOIN messages_tags ON messages.ID_MESSAGE = messages_tags.ID_MESSAGE
						WHERE ID_TAG = \'' . $id_tag . '\' ORDER BY DATE_MESS DESC LIMIT ' . ($page_number - 1) * $nb_max_msg . ' , ' . $nb_max_msg . ' ';
        $msg_list = execute_query($dbLink, $query);
    }

    return $msg_list;
}

function insert_msg_db($uid, $msg, $img, $dbLink) {

    $query = 'SELECT * FROM parametres';
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    $n_min = $dbRow['N_MIN'];
    $n_max = $dbRow['N_MAX'];

    $nb_avant_don = rand($n_max, $n_min);

    $query = 'INSERT INTO messages (ID_USER, MESSAGE, IMG, NB_AVANT_DON) VALUES 
                                                                        (\'' . $uid . '\',
                                                                        \'' . $msg . '\',
                                                                        \'' . $img . '\',
                                                                        \'' . $nb_avant_don . '\')';
    execute_query($dbLink, $query);
    return mysqli_insert_id($dbLink);
}

function manage_tag($msg, $dbLink, $id_msg) {

    // Extraction des tags dans le message
    preg_match_all('/ß([\w]+)/', $msg, $tags);

    //Manage du tag
    foreach ($tags[1] as $val) {

        //Verification de l'existance du tag
        $query = 'SELECT ID_TAG FROM tags WHERE NOM_TAG = \'' . $val . '\'';
        $dbResult = execute_query($dbLink, $query);

        if (mysqli_num_rows($dbResult) == 0) {

            //Creation du tag
            $query = 'INSERT INTO tags (NOM_TAG) VALUES (\'' . $val . '\')';
            execute_query($dbLink, $query);
            $id_tag = mysqli_insert_id($dbLink);
        }

        else {
            $dbRow = mysqli_fetch_assoc($dbResult);
            $id_tag = $dbRow['ID_TAG'];
        }

        //Insertion de id_tag et id_msg dans messages_tags
        $query = 'INSERT INTO messages_tags (ID_TAG, ID_MESSAGE) VALUES                                                                     
                                                                    (\'' . $id_tag . '\',
                                                                    \'' . $id_msg . '\')';
        execute_query($dbLink, $query);
    }
}

function display_msg($dbLink, $tag, $page_number, $nb_max_msg): int
{
    if ($tag != NULL) {
        $dbResult = search_tag($tag, $dbLink, $page_number, $nb_max_msg);
    }
    else {
        $query = 'SELECT ID_MESSAGE, MESSAGE, ID_USER, DATE_MESS, DONNE, DON_USER FROM messages ORDER BY DATE_MESS DESC LIMIT ' . ($page_number - 1) * $nb_max_msg . ' , ' . $nb_max_msg;
        $dbResult = execute_query($dbLink, $query);
    }

    $nb_ligne = 0;

    while ($dbRow = mysqli_fetch_assoc($dbResult)) {

        //au moins une ligne à afficher
        $nb_ligne = $nb_ligne + 1;

        $querynom = 'SELECT PSEUDO FROM users WHERE ID_USER = \'' . $dbRow['ID_USER'] . '\'';
        $dbResultNom = execute_query($dbLink, $querynom);

        $dbRowNom = mysqli_fetch_assoc($dbResultNom);

        echo '<section>' , PHP_EOL;
        echo '<div class = TitreMsg>', $dbRowNom['PSEUDO'], '</div>'  , PHP_EOL;

        echo '<div class = message>', $dbRow['MESSAGE'], '</div>'  , PHP_EOL;

        reactions($dbRow['ID_MESSAGE'], $dbLink);

        echo '<div class = date_mess>', $dbRow['DATE_MESS'], '</div>'  , PHP_EOL;
        echo '</section>' , PHP_EOL;

        if($dbRow['DONNE'] == 'N') {
            if ($dbRow['DON_USER'] == $_SESSION['user_id']) {
                echo 'coucou2222';
                $query = 'UPDATE messages SET DONNE = \'Y\' WHERE ID_MESSAGE = ' . $dbRow['ID_MESSAGE'];
                execute_query($dbLink, $query);

                echo '<script> alert("Félicitation ! vous devez donner 10 bitcoins à Vanéstarre !") </script>';
            }
        }
    }

    if($nb_ligne == 0) {
        echo '<section>' , PHP_EOL;
        echo 'Fin des messages', PHP_EOL;
        echo '</section>' , PHP_EOL;
    }

    return $nb_ligne;
}

function navbar()
{
    echo '<a href="index.php"><i class="fa fa-home"></i>Accueil</a>' . PHP_EOL;
    if (isset($_SESSION['loggedin'])) {
        echo "\t\t" . '<a href="parametre.php"><i class="fa fa-cog"></i>Paramètres</a>' . PHP_EOL;
        echo "\t\t" . '<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>' . PHP_EOL;
    }
    else {
        echo "\t\t" . '<a href="login.php"><i class="fas fa-sign-in-alt"></i>Se connecter</a>' . PHP_EOL;
        echo "\t\t" . '<a href="registration.php"><i class="fa fa-user-plus"></i>S\'inscrire</a>' . PHP_EOL;
    }
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

function pagination ($tag, $page_number, $nb_ligne, $nb_max_msg) {
    if($page_number > 1)
        echo '<a href="index.php?search=' . $tag . '&page=' . ($page_number - 1) . ' "><</a>' . PHP_EOL;

    echo ' ' . $page_number . ' ' . PHP_EOL;

    if(!($nb_ligne ==0 || $nb_ligne < $nb_max_msg))
        echo '<a href="index.php?search=' . $tag . '&page=' . ($page_number + 1) . ' ">></a>' . PHP_EOL;
}

function reactions($id_mess, $dbLink) {

    $query = 'SELECT NOTE, COUNT(*) FROM notes WHERE ID_MESSAGE = \'' . $id_mess . '\' GROUP BY NOTE';
    $dbResult = execute_query($dbLink, $query);

    $nb_love = 0;
    $nb_cute = 0;
    $nb_swag = 0;
    $nb_style = 0;

    $note = '';

    if (mysqli_num_rows($dbResult) != 0) {
        while($dbRow = mysqli_fetch_assoc($dbResult)) {
            if($dbRow['NOTE'] == 'L') $nb_love = $dbRow['COUNT(*)'];
            if($dbRow['NOTE'] == 'C') $nb_cute = $dbRow['COUNT(*)'];
            if($dbRow['NOTE'] == 'S') $nb_swag = $dbRow['COUNT(*)'];
            if($dbRow['NOTE'] == 'T') $nb_style = $dbRow['COUNT(*)'];
        }

        if(isset($_SESSION['loggedin'])) {
            $query = 'SELECT NOTE FROM notes WHERE ID_USER = \'' . $_SESSION['user_id'] . '\' AND ID_MESSAGE = \'' . $id_mess . '\' GROUP BY NOTE';
            $dbResult = execute_query($dbLink, $query);

            if(mysqli_num_rows($dbResult) != 0) {
                $dbRow = mysqli_fetch_assoc($dbResult);
                $note = $dbRow['NOTE'];
            }
        }
    }

    echo '<div class="reactions">' , PHP_EOL;
    echo '<div class ="icone_';
    if($note == 'L') echo 'on">'; else echo 'off">';
    echo '<a href="reaction.php?id_m='. $id_mess . '&icone=L"> <img src="assets/Images/love.png"> </a>', $nb_love , PHP_EOL,
    '</div>' , PHP_EOL;

    echo '<div class ="icone_';
    if($note == 'C') echo 'on">'; else echo 'off">';
    echo '<a href="reaction.php?id_m='. $id_mess . '&icone=C"> <img src="assets/Images/cute.png"> </a>', $nb_cute , PHP_EOL,
    '</div>' , PHP_EOL;

    echo '<div class ="icone_';
    if($note == 'S') echo 'on">'; else echo 'off">';
    echo '<a href="reaction.php?id_m='. $id_mess . '&icone=S"> <img src="assets/Images/swag.png"> </a>', $nb_swag , PHP_EOL,
    '</div>' , PHP_EOL;

    echo '<div class ="icone_';
    if($note == 'T') echo 'on">'; else echo 'off">';
    echo '<a href="reaction.php?id_m='. $id_mess . '&icone=T"> <img src="assets/Images/style.png"> </a>', $nb_style , PHP_EOL,
    '</div>' , PHP_EOL,
    '</div>' , PHP_EOL;
}

function display_membres($dbLink) {

    $query = 'SELECT PSEUDO, EMAIL, ID_USER FROM users WHERE ID_USER !=' . $_SESSION['user_id'];
    $dbResult = execute_query($dbLink, $query);

    if (mysqli_num_rows($dbResult) != 0) {
        while ($dbRow = mysqli_fetch_assoc($dbResult)) {
            $pseudo = $dbRow['PSEUDO'];
            $email = $dbRow['EMAIL'];
            $id_user = $dbRow['ID_USER'];

            echo '<form action="parametre.php" method="post">' . PHP_EOL .
                '<input type="text" name="pseudo" value="' . $pseudo . '" required/>' . PHP_EOL .
                '<input type="text" name="email" value="' . $email . '" required/>' . PHP_EOL .
                '<input type="submit" name="action_update" value="Modifier"/>' . PHP_EOL .
                '<input type="submit" name="action_delete" value="Supprimer"/>' . PHP_EOL .
                '<input type="hidden" name="id_user" value="' . $id_user . '"/>' . PHP_EOL .
                '</form>' . PHP_EOL;
        }
    }
}

function display_param($dbLink) {

    $query = 'SELECT * FROM parametres';
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    $n_msg = $dbRow['N_MSG'];
    $n_min = $dbRow['N_MIN'];
    $n_max = $dbRow['N_MAX'];

    echo '<form action="parametre.php" method="post">' . PHP_EOL .

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

function display_error($post_id, $error) {
    if (isset($_POST[$post_id])) {
        echo '<section>' . PHP_EOL;
        echo '<p>' . $error . '</p>' . PHP_EOL;
        echo '</section>' . PHP_EOL;
    }
}

function get_n_mes($dbLink): int
{

    $query = 'SELECT N_MSG FROM parametres WHERE TRUE';
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    return $dbRow['N_MSG'];
}

function get_role($dbLink, $id_user): string
{

    $query = 'SELECT ROLE_USER FROM users WHERE ID_USER =' . $id_user;
    $dbResult = execute_query($dbLink, $query);

    $dbRow = mysqli_fetch_assoc($dbResult);
    return $dbRow['ROLE_USER'];
}