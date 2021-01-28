<?php


//on s'occupe de la partie des tags pour l'insertion de message
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


//on supprime les liens entre le message et le ou les tags
function delete_linked_tag($id_msg, $dbLink) {

    $query = 'DELETE FROM messages_tags WHERE ID_MESSAGE = ' . $id_msg;
    execute_query($dbLink, $query);
}
