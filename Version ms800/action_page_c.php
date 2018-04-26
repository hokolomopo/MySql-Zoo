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
    
    get_style_overlay();

    get_style_return_button();

    get_style_table();

    echo <<< EOT

    </style>
    </head>
    <body>
EOT;

    get_body_overlay();

    begin_main();

    try
    {
        $bdd = new PDO(get_pdo_path(), get_pdo_user(), get_pdo_password());
    }
    catch (Exception $e)
    {
        exit("Une erreur inattendue est survenue lors de la connexion à la base de donnée : " . $e->getMessage());
    }

    $résultats = execute_sql_classique($bdd, "c_techniciens.sql", null);

    if(count($résultats) == 0)
        echo "Pas de résultats </br>";

    else{
        echo "Les techniciens qui ont travaillé dans l'ensemble des enclos du parc animalier sont:</br></br>";
    
        affiche_tableau($résultats, "Techniciens");
    }

    echo "</br>";

    get_body_return_button('page_c.php');

    end_main();

    echo <<<EOT
    </body>
    </html>
EOT;
}

else{
    header('Location: connexion.php');
}

?>
   