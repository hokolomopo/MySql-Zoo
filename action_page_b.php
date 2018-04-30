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
        $bdd = new PDO(get_pdo_path(), $_SESSION['uname'], $_SESSION['password']);
    }
    catch(Exception $e)
    {
        header('Location: connexion.php');
    }

    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $page_de_retour = "page_b.php";

    try {
        $résultats = execute_sql_classique($bdd, "b_tri_animaux.sql", null);
    } catch (Exception $e) {
        echo "La requête n'a pas pu être exécutée pour une raison inconnue, la table n'existe peut être pas";
        bouton_retour($page_de_retour);
        exit(1);
    }

    if(count($résultats) == 0)
        echo "Pas de résultats </br>";

    else{
        affiche_tableau($résultats, "Liste des animaux, triée par le nombre de vétérinaires différents étant intervenus au moins une fois sur eux");
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

    
