<?php 

session_start();

include 'overlay.php';
include 'return_button.php';
include 'db_connect.php';
include 'print_table.php';
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
    
    style_fond();

    style_bouton_retour();

    style_tableau();

    echo <<< EOT

    </style>
    </head>
    <body>
EOT;

    corps_fond();

    debut_main();

    try
    {
        $bdd = new PDO(adresse_pdo(), $_SESSION['uname'], $_SESSION['password']);
    }
    catch(Exception $e)
    {
        header('Location: connexion.php');
    }

    $page_de_retour = "page_c.php";

    $résultats = execute_sql_classique($bdd, "c_techniciens.sql", null);

    if(count($résultats) == 0)
        echo "Pas de résultats </br>";

    else{    
        affiche_tableau($résultats, "Liste des techniciens ayant travaillé dans l'ensemble des enclos du parc animalier");
    }

    echo "</br>";

    bouton_retour($page_de_retour);

    fin_main();

    echo <<<EOT
    </body>
    </html>
EOT;
}

else{
    header('Location: connexion.php');
}

?>
   