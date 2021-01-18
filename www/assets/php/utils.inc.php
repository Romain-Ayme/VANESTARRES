<?php
function start_page($title) {
    echo '<!DOCTYPE html>' . PHP_EOL .
        '<html lang="fr">' . PHP_EOL .
        "\t" . '<head>' . PHP_EOL .
        "\t\t" . '<meta charset="utf-8">' . PHP_EOL .
        "\t\t" . '<link rel="icon" type="image/png" href="assets/img/VANESTARRE.png" />' . PHP_EOL .
        "\t\t" . '<title>' . $title . '</title>' . PHP_EOL .
        //"\t\t" . '<link rel="stylesheet" href="assets/css/' . $css . '.css">' . PHP_EOL .
        "\t\t" . '<link rel="stylesheet" href="assets/css/normalize.css">' . PHP_EOL .
        "\t" . '</head>' . PHP_EOL .
        "\t" . '<body>' . PHP_EOL;
}

function end_page() {
    echo "\t" . '</body>' . PHP_EOL
        . '</html>';
}

function m_header() {
    echo "\t" . '<header>' . PHP_EOL .
        "\t\t" . '<a href="index.php">Venastarre</a>' . PHP_EOL .
        "\t\t" . '<input type="search">' . PHP_EOL .
        "\t" . '</header>' . PHP_EOL;
}

function section_tag() {
    echo "\t" . '<section>' . PHP_EOL .
        "\t\t" . 'tag' . PHP_EOL .
        "\t" . '</section>' .PHP_EOL;
}

function section_mp() {
    echo "\t" . '<section>' . PHP_EOL .
        "\t\t" . 'mp' . PHP_EOL .
        "\t" . '</section>' . PHP_EOL;
}
function footer() {
    echo "\t" . '<footer>' . PHP_EOL .
        "\t\t" . 'footer' . PHP_EOL .
        "\t" . '</footer>' . PHP_EOL;
}