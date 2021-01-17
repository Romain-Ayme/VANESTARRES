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

function search_tag($tag, $dbLink) {

    $query = 'SELECT ID_TAG FROM tags WHERE NOM_TAG = \'' . $tag . '\'';
    $dbResult = execute_query($dbLink, $query);

    $msg_list = NULL;

    if (mysqli_num_rows($dbResult) != 0) {
        $dbRow = mysqli_fetch_assoc($dbResult);
        $id_tag = $dbRow['ID_TAG'];

        $query = 'SELECT MESSAGE, ID_USER, NB_LOVE, NB_CUTE, NB_STYLE, NB_SWAG
						FROM messages
						JOIN Messages_tags ON messages.ID_MESSAGE = Messages_tags.ID_MESSAGE
						WHERE ID_TAG = \'' . $id_tag . '\'';
        $msg_list = execute_query($dbLink, $query);
    }

    return $msg_list;
}

function insert_msg_db($uid, $msg, $img, $dbLink) {

    $query = 'INSERT INTO messages (ID_USER, MESSAGE, IMG) VALUES 
                                                                    (\'' . $uid . '\',
                                                                    \'' . $msg . '\',
                                                                    \'' . $img . '\')';
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
        $query = 'INSERT INTO Messages_tags (ID_TAG, ID_MESSAGE) VALUES                                                                     
                                                                    (\'' . $id_tag . '\',
                                                                    \'' . $id_msg . '\')';
        execute_query($dbLink, $query);
    }
}

function display_msg($dbLink, $tag)
{
    if ($tag != NULL) {
        $dbResult = search_tag($tag, $dbLink);
    } else {
        $query = 'SELECT MESSAGE, ID_USER, NB_LOVE, NB_CUTE, NB_STYLE, NB_SWAG FROM messages';
        $dbResult = execute_query($dbLink, $query);
    }

    while ($dbRow = mysqli_fetch_assoc($dbResult)) {

        $querynom = 'SELECT PSEUDO FROM users WHERE ID_USER = \'' . $dbRow['ID_USER'] . '\'';
        $dbResultNom = execute_query($dbLink, $querynom);

        $dbRowNom = mysqli_fetch_assoc($dbResultNom);

        echo '<section>';
        echo '<div class = TitreMsg>', $dbRowNom['PSEUDO'], '</div>'; // Undefined index: PSEUDO

        echo '<div class = message>', $dbRow['MESSAGE'], '</div>';

        echo '<div class = love>',
        $dbRow['NB_LOVE'], '<img class="reaction" src="assets/img/love.png">',
        '</div>',

        '<div class = cute>',
        $dbRow['NB_CUTE'], '<img class="reaction" src="assets/img/cute.png">',
        '</div>',

        '<div class = swag>',
        $dbRow['NB_SWAG'], '<img class="reaction" src="assets/img/swag.png">',
        '</div>',

        '<div class = style>',
        $dbRow['NB_STYLE'], '<img class="reaction" src="assets/img/style.png">',
        '</div>';
        echo '</section>';
    }
}

function navbar()
{
    echo '<a href="index.php"><i class="fa fa-home"></i>Accueil</a>' . PHP_EOL;
    if (isset($_SESSION['loggedin'])) {
        echo "\t\t" . '<a href="profile.php"><i class="fa fa-cog"></i>Paramètres</a>' . PHP_EOL;
        echo "\t\t" . '<a href="home.php"><i class="fas fa-user-circle"></i>Mon Compte</a>' . PHP_EOL;
        echo "\t\t" . '<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Se déconnecter</a>' . PHP_EOL;
    }
    else {
        echo "\t\t" . '<a href="login.php"><i class="fas fa-sign-in-alt"></i>Se connecter</a>' . PHP_EOL;
        echo "\t\t" . '<a href="registration.php"><i class="fa fa-user-plus"></i>S\'inscrire</a>' . PHP_EOL;
    }
}