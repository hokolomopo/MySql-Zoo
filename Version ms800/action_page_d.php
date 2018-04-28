<?php 

session_start();

include 'overlay.php';
include 'return_button.php';
include 'db_connect.php';
include 'execute_sql.php';

if(array_key_exists('connected', $_SESSION) and $_SESSION['connected']){
    echo <<< EOT
    <!DOCTYPE html>
    <html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">

    <style>
EOT;
    
    get_style_overlay();

    get_style_return_button();

    echo <<< EOT
    
    </style>
    </head>
    <body>
EOT;

    get_body_overlay();

    begin_main();

    try
    {
        $bdd = new PDO(get_pdo_path(), $_SESSION['uname'], $_SESSION['password']);
    }
    catch(Exception $e)
    {
        header('Location: connexion.php');
    }

    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $page_de_retour = "page_d.php";

    try {
        $résultats = execute_sql_classique($bdd, "d_proportion.sql", null);
    } catch (Exception $e) {
        echo "La requête n'a pas pu être exécutée pour une raison inconnue, la table n'existe peut être pas";
        get_body_return_button($page_de_retour);
        exit(1);
    }

    $proportion = $résultats[0]['proportion'];

    echo "La proportion d'interventions qui ont été effectuées sur des animaux présents dans un enclos dont le climat ";
    echo "ne correspond pas à l'un de ceux supportés par son espèce est de:</br>";
    echo doubleval($proportion)*100;
    echo "%";

    get_body_return_button($page_de_retour);

    end_main();

    echo <<< EOT
    </body>
    </html>
EOT;
}

else{
    header('Location: connexion.php');
}

?>