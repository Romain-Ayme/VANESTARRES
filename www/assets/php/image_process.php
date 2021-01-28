<?php

//nomme le fichier en image."son type"
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

//sauvegarde l'image dans le fichier correspondant
function save_img($id_msg): string
{

    $repertoireDestination = '../../Public/msg/' . $id_msg . '/';
    $nom_img = nommage($_FILES['img']['type']);

    if (!is_dir($repertoireDestination)) {

        //Si le repertoire n'existe pas, on le crée
        mkdir($repertoireDestination, 0777, true);
    }

    //on supprime les messages deja existant
    else {
        array_map('unlink', glob($repertoireDestination.'*'));
    }

    //on met l'image dans le fichier
    move_uploaded_file($_FILES["img"]["tmp_name"] , $repertoireDestination.$nom_img);

    return $repertoireDestination.$nom_img;
}

//on supprime totalement l'image et le fichier
function delete_img($id_msg)
{

    $repertoireDestination = 'Public/msg/' . $id_msg . '/';

    if (is_dir($repertoireDestination)) {

        //on efface l'image
        array_map('unlink', glob($repertoireDestination.'*'));

        //on efface le repertoire
        rmdir($repertoireDestination);
    }
}