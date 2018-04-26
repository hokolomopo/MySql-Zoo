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
        $bdd = new PDO(get_pdo_path(), get_pdo_user(), get_pdo_password());
    }
    catch (Exception $e)
    {
        exit("Une erreur inattendue est survenue lors de la connexion à la base de donnée : " . $e->getMessage());
    }

    $résultats = execute_sql_classique($bdd, "d_proportion.sql", null);
    $proportion = $résultats[0]['proportion'];

    echo "La proportion d'interventions qui ont été effectuées sur des animaux présents dans un enclos dont le climat";
    echo "ne correspond pas à l'un de ceux supportés par son espèce est de:</br>";
    echo doubleval($proportion)*100;
    echo "%";

    get_body_return_button('page_d.php');

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