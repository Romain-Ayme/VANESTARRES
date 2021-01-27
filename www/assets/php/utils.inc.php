<?php

    //head de la page
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

    //fin de la page
    function end_page()
    {
        ?>
            </body>
        </html>
        <?php
    }


    //Connexion à la base de donnée
    function connect_db()
    {
        $dbLink = mysqli_connect('mysql-romain-ayme.alwaysdata.net', '223609_php', 'zK7dQm4H3')
        or die('Erreur de connexion au serveur : ' . mysqli_connect_error());
        mysqli_select_db($dbLink, 'romain-ayme_vanestarre')
        or die('Erreur dans la sélection de la base : ' . mysqli_error($dbLink));
        return $dbLink;
    }


    //execution de la requête, si elle ne marche pas, on l'affiche
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


    //Recherche des messages qui on le tag qu'on recherche
    function search_tag($tag, $dbLink, $page_number, $nb_max_msg) {

        $query = 'SELECT ID_TAG FROM tags WHERE NOM_TAG = \'' . $tag . '\'';
        $dbResult = execute_query($dbLink, $query);

        $msg_list = NULL;

        if (mysqli_num_rows($dbResult) != 0) {
            $dbRow = mysqli_fetch_assoc($dbResult);
            $id_tag = $dbRow['ID_TAG'];

            //On recupere tous les messages qui ont le tag qu'on recherche
            $query = 'SELECT messages.ID_MESSAGE, MESSAGE, ID_USER, DATE_MESS, IMG, DONNE, DON_USER
                            FROM messages
                            JOIN messages_tags ON messages.ID_MESSAGE = messages_tags.ID_MESSAGE
                            WHERE ID_TAG = ' . $id_tag . ' ORDER BY DATE_MESS DESC LIMIT ' . ($page_number - 1) * $nb_max_msg . ' , ' . $nb_max_msg . ' ';
            $msg_list = execute_query($dbLink, $query);
        }

        return $msg_list;
    }


    //On insert le message dans la BDD
    function insert_msg_db($uid, $msg, $dbLink, $id_mess) {

        if($id_mess == NULL) {

            //On recupere le min et max avant don
            $query = 'SELECT * FROM parametres';
            $dbResult = execute_query($dbLink, $query);

            $dbRow = mysqli_fetch_assoc($dbResult);
            $n_min = $dbRow['N_MIN'];
            $n_max = $dbRow['N_MAX'];

            //On choisi un nombre avant don entre min et max
            $nb_avant_don = rand($n_max, $n_min);

            $query = 'INSERT INTO messages (ID_USER, MESSAGE, NB_AVANT_DON) VALUES 
                                                                            (\'' . $uid . '\',
                                                                            \'' . $msg . '\',
                                                                            \'' . $nb_avant_don . '\')';
            execute_query($dbLink, $query);
            return mysqli_insert_id($dbLink);
        }

        else {
            $query = 'UPDATE messages SET MESSAGE = \'' . $msg . '\' WHERE ID_MESSAGE = ' . $id_mess;
            execute_query($dbLink, $query);
            return $id_mess;
        }
    }

    function update_img_db ($id_mess, $img_path, $dbLink) {

        $query = 'UPDATE messages SET IMG = \'' . $img_path . '\' WHERE ID_MESSAGE = ' . $id_mess;
        execute_query($dbLink, $query);
    }

    //On s'occupe de la partie des tags
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


    function delete_linked_tag($id_msg, $dbLink) {

        $query = 'DELETE FROM messages_tags WHERE ID_MESSAGE = ' . $id_msg;
        execute_query($dbLink, $query);
    }

    function delete_note($id_msg, $dbLink) {
        $query = 'DELETE FROM notes WHERE ID_MESSAGE = ' . $id_msg;
        execute_query($dbLink, $query);
    }


    //On affiche les messages
    function display_msg($dbLink, $tag, $page_number, $nb_max_msg, $role): int
    {

        //Si on recherche un tag, on execute la fonction qui va rechercher tous les messages qui sont associés au tag
        if ($tag != NULL) {
            $dbResult = search_tag($tag, $dbLink, $page_number, $nb_max_msg);
        }

        //Sinon, on recupere tous les messages de la BDD (le nombre de message qu'on recupere depend du nombre de message qu'on affiche par page)
        else {
            $query = 'SELECT ID_MESSAGE, MESSAGE, ID_USER, DATE_MESS, IMG, DONNE, DON_USER FROM messages ORDER BY DATE_MESS DESC LIMIT ' . ($page_number - 1) * $nb_max_msg . ' , ' . $nb_max_msg;
            $dbResult = execute_query($dbLink, $query);
        }

        $nb_ligne = 0;

        while ($dbRow = mysqli_fetch_assoc($dbResult)) {

            //au moins une ligne à afficher
            $nb_ligne = $nb_ligne + 1;

            $querynom = 'SELECT PSEUDO FROM users WHERE ID_USER = ' . $dbRow['ID_USER'];
            $dbResultNom = execute_query($dbLink, $querynom);

            $dbRowNom = mysqli_fetch_assoc($dbResultNom);

            echo '<section>' , PHP_EOL;
            echo '<div class="titreMsg">', $dbRowNom['PSEUDO'], '</div>' , PHP_EOL;

            //Si il y a une image, on l'affiche
            if($dbRow['IMG'] != NULL) {
                echo '<img class="image" alt="" src="' . $dbRow['IMG'] . '"/>' . PHP_EOL;
            }

            echo '<div class="message">', $dbRow['MESSAGE'], '</div>' , PHP_EOL;

            //On affiche les icones(reactions)
            reactions($dbRow['ID_MESSAGE'], $dbLink);

            echo '<div class="date_mess">', $dbRow['DATE_MESS'], '</div>'  , PHP_EOL;

            //Si l'utilisateur actuel est administrateur, on affiche le bouton modifier et supprimer
            if($role == 'SUPER') {
                echo '<div class="bouton_modif">'. PHP_EOL .
                    '<form action="creation_msg.php" method="post">'. PHP_EOL .
                    '<input type="hidden" name="id_m" value="' . $dbRow['ID_MESSAGE'] . '"/>'. PHP_EOL .
                    '<input type="submit" name="modifier" value="Modifier"/>'. PHP_EOL .
                    '<input type="submit" name="supprimer" value="Supprimer"/>'. PHP_EOL .
                    '</form>'. PHP_EOL .
                    '</div>'. PHP_EOL;
            }
            echo '</section>' , PHP_EOL;

            //Si il y a un utilisateur connecté, et qu'il faut donner un don, alors on affiche un message d'alerte
            if(isset($_SESSION['loggedin'])) {
                if($dbRow['DONNE'] == 'N') {
                    if ($dbRow['DON_USER'] == $_SESSION['user_id']) {
                        $query = 'UPDATE messages SET DONNE = \'Y\' WHERE ID_MESSAGE = ' . $dbRow['ID_MESSAGE'];
                        execute_query($dbLink, $query);

                        echo '<script> alert("Félicitation ! vous devez donner 10 bitcoins à Vanéstarre !") </script>';
                    }
                }
            }
        }

        //Si il n'y a plus de message à afficher, on previent l'utilisateur
        if($nb_ligne == 0) {
            echo '<section>' , PHP_EOL;
            echo 'Fin des messages', PHP_EOL;
            echo '</section>' , PHP_EOL;
        }

        return $nb_ligne;
    }


    //On affiche la navbar
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


    //On affiche les 10 tags les plus populaires
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


    //On affiche le numero de la page et les boutons pour pouvoir changer de page
    function pagination ($tag, $page_number, $nb_ligne, $nb_max_msg) {
        if($page_number > 1)
            echo '<a href="index.php?search=' . $tag . '&page=' . ($page_number - 1) . ' "><</a>' . PHP_EOL;

        echo ' ' . $page_number . ' ' . PHP_EOL;

        if(!($nb_ligne ==0 || $nb_ligne < $nb_max_msg))
            echo '<a href="index.php?search=' . $tag . '&page=' . ($page_number + 1) . ' ">></a>' . PHP_EOL;
    }


    //On affiche le nombre de chaque réaction qu'un message a obtenu
    function reactions($id_mess, $dbLink) {

        $query = 'SELECT NOTE, COUNT(*) FROM notes WHERE ID_MESSAGE = ' . $id_mess . ' GROUP BY NOTE';
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
                $query = 'SELECT NOTE FROM notes WHERE ID_USER = ' . $_SESSION['user_id'] . ' AND ID_MESSAGE = ' . $id_mess . ' GROUP BY NOTE';
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
        echo '<a href="assets/php/reaction.php?id_m='. $id_mess . '&icone=L"> <img src="assets/Images/love.png"> </a>', $nb_love , PHP_EOL,
        '</div>' , PHP_EOL;

        echo '<div class ="icone_';
        if($note == 'C') echo 'on">'; else echo 'off">';
        echo '<a href="assets/php/reaction.php?id_m='. $id_mess . '&icone=C"> <img src="assets/Images/cute.png"> </a>', $nb_cute , PHP_EOL,
        '</div>' , PHP_EOL;

        echo '<div class ="icone_';
        if($note == 'S') echo 'on">'; else echo 'off">';
        echo '<a href="assets/php/reaction.php?id_m='. $id_mess . '&icone=S"> <img src="assets/Images/swag.png"> </a>', $nb_swag , PHP_EOL,
        '</div>' , PHP_EOL;

        echo '<div class ="icone_';
        if($note == 'T') echo 'on">'; else echo 'off">';
        echo '<a href="assets/php/reaction.php?id_m='. $id_mess . '&icone=T"> <img src="assets/Images/style.png"> </a>', $nb_style , PHP_EOL,
        '</div>' , PHP_EOL,

        '</div>' , PHP_EOL;
    }


    //On affiche tous les membres du site
    function display_membres($dbLink) {

        $query = 'SELECT PSEUDO, EMAIL, ID_USER, DELETED FROM users WHERE ID_USER !=' . $_SESSION['user_id'];
        $dbResult = execute_query($dbLink, $query);

        if (mysqli_num_rows($dbResult) != 0) {
            while ($dbRow = mysqli_fetch_assoc($dbResult)) {
                $pseudo = $dbRow['PSEUDO'];
                $email = $dbRow['EMAIL'];
                $id_user = $dbRow['ID_USER'];
                $is_deleted = $dbRow['DELETED'];

                echo '<form action="parametre.php" method="post">' . PHP_EOL;

                    if($is_deleted == 'Y') {

                        echo '<input type="text" name="pseudo" value="' . $pseudo . '" disabled/>' . PHP_EOL .
                            '<input type="text" name="email" value="' . $email . '" disabled/>' . PHP_EOL .
                            '<input type="submit" name="action_toggle" value="Réactiver"/>' . PHP_EOL;
                    }
                    else {
                        echo '<input type="text" name="pseudo" value="' . $pseudo . '" required/>' . PHP_EOL .
                            '<input type="text" name="email" value="' . $email . '" required/>' . PHP_EOL .
                            '<input type="submit" name="action_update" value="Modifier"/>' . PHP_EOL .
                            '<input type="submit" name="action_toggle" value="Désactiver"/>' . PHP_EOL;
                    }
                    echo '<input type="hidden" name="id_user" value="' . $id_user . '"/>' . PHP_EOL .
                    '</form>' . PHP_EOL;
            }
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


    //On affiche si la demande à reussi, sinon, on affiche pourquoi
    function display_error($post_id, $error) {
        if (isset($_POST[$post_id])) {
            echo '<section>' . PHP_EOL;
            echo '<p>' . $error . '</p>' . PHP_EOL;
            echo '</section>' . PHP_EOL;
        }
    }


    //On obtient le nombre de message à afficher par page
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

        $query = 'SELECT ROLE_USER FROM users WHERE ID_USER = ' . $id_user;
        $dbResult = execute_query($dbLink, $query);

        $dbRow = mysqli_fetch_assoc($dbResult);
        return $dbRow['ROLE_USER'];
    }

    function nommage($type): string
    {
        if ($type == 'image/png') {
            return 'image.png';
        }

        elseif ($type == 'image/gif') {
            return 'image.gif';
        }

        elseif ($type == 'image/jpg') {
            return 'image.jpg';
        }

        else  {
            return 'image.jpeg';
        }
    }


    function delete_img($id_msg)
    {

        $repertoireDestination = '../public/image_msg/' . $id_msg . '/';

        if (is_dir($repertoireDestination)) {

            //on efface l'image
            array_map('unlink', glob($repertoireDestination.'*'));

            //on efface le repertoire
            rmdir($repertoireDestination);
        }
    }


    function save_img($id_msg): string
    {

        $repertoireDestination = '../../../public/image_msg/' . $id_msg . '/';
        $nom_img = nommage($_FILES['img']['type']);

        if (!file_exists($repertoireDestination)) {

            //Si le repertoire n'existe pas, on le crée
            mkdir($repertoireDestination, 0777, true);
        }

        else {
            array_map('unlink', glob($repertoireDestination.'*'));
        }

        move_uploaded_file($_FILES["img"]["tmp_name"] , $repertoireDestination.$nom_img);

        return $repertoireDestination.$nom_img;
    }