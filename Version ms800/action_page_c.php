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

    $executable = $bdd->prepare(file_get_contents('c_techniciens.sql'));
    $executable->execute();
    echo "Les techniciens qui ont travaillé dans l'ensemble des enclos du parc animalier sont:</br>";
    echo "Numéro de registre / Nom / Prénom</br></br>";

    $fetch_resultat = $executable->fetch();
    while ($fetch_resultat) {
        for ($x = 0; $x < count($fetch_resultat)/2; $x++) { //division par 2 car les éléments sont duppliqués dans le fetch().
            echo $fetch_resultat[$x] . "\t";
        }
        echo "</br>";
        $fetch_resultat = $executable->fetch();
    }

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
   