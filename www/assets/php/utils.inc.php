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