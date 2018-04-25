<?php 

session_start();

include 'overlay.php';
include 'return_button.php';
include 'db_connect.php';

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
        die('Erreur : ' . $e->getMessage());
    }

    $executable = $bdd->prepare(file_get_contents('d_proportion.sql'));
    $executable->execute();
    echo "La proportion d'interventions qui ont été effectuées sur des animaux présents dans un enclos dont le climat";
    echo "ne correspond pas à l'un de ceux supportés par son espèce est de:</br>";
    $requestResult = $executable->fetch();
    $proportion = $requestResult['proportion'];
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