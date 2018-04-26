<?php 

session_start();

include 'overlay.php';
include 'return_button.php';
include 'db_connect.php';
include 'print_table.php';

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
        die('Erreur : ' . $e->getMessage());
    }

    $executable = $bdd->prepare(file_get_contents('b_tri_animaux.sql'));
    $executable->execute();

    $result = $executable->fetchAll();

    if(count($result) == 0)
        echo "Pas de résultats </br>";

    else{
        echo "Voici la liste, triée par le nombre de vétérinaires différents étant intervenus au moins une fois sur eux, des animaux:</br></br>";

        echo '<table>';

        print_key_line($result[0]);

        foreach($result as $data)
        {
            print_value_line($data);
        }    

        echo '</table>';
    }

    echo "</br>";
    get_body_return_button('page_b.php');

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

    
