<?php 

session_start();

include 'overlay.php';
include 'return_button.php';
include 'db_connect.php';
include 'print_table.php';

if(array_key_exists('connected', $_SESSION) and $_SESSION['connected']) {
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

    function invalid_request()
    {
         exit("requête invalide, veuillez utiliser le formulaire de la page_a afin de faire les requêtes et ne pas envoyer vos propres requêtes au serveur.");
    }

    try
    {
        $bdd = new PDO(get_pdo_path(), get_pdo_user(), get_pdo_password());
    }
    catch (Exception $e)
    {
            die('Erreur : ' . $e->getMessage());
    }

    $request = 'SELECT * FROM ' . $_POST['table'];
    $first = true;

    foreach($_POST as $cle => $valeur){
        
        if(!array_key_exists('table', $_POST))
            invalid_request();

        //empeche l'utilisateur de placer des balises html et donc d'exécuter du javascript
        $cle = htmlspecialchars($cle);
        $valeur = htmlspecialchars($valeur);

        if($cle != 'table'){

            $info_exe = $bdd->prepare(file_get_contents('get_db_information.sql'));
            $info_exe -> execute(array(':table' => $_POST['table'], ':column' => $cle));
            $info = $info_exe -> fetchAll();

            $nb_elem = count($info);

            if($nb_elem == 0)
                invalid_request();

            if($valeur != null){

                //check data validity
                $type = $info[0]['data_type'];
                $valeur_is_string = false;
                switch($type){

                    case 'char':
                    case 'varchar':
                        
                        //it is anyway a string, we thus assert that what's inside is not a number
                        $test_valeur = $valeur;
                        if(substr($valeur, 0, 1) == "-")
                            $test_valeur = substr($valeur, 1);
                        
                        if(ctype_digit($test_valeur))
                            exit("le champ " . $cle . " doit contenir une chaine de caractère");

                        if(strlen($valeur) > $info[0]['character_maximum_length'])
                            exit("le champ " . $cle . "doit contenir " . $info[0]['character_maximum_length'] . " caractères maximum");

                        $valeur_is_string = true;

                        break;

                    case 'datetime':
                        
                        if (!(preg_match('#^([0-9]{4}).([0-9]{2}).([0-9]{2})$#', $valeur, $date_tableau) == 1 && checkdate($date_tableau[2], $date_tableau[3], $date_tableau[1])))
                            exit("le format de " . $cle . " ne correspond pas au format attendu par le serveur");

                        break;

                    case 'smallint':
                    case 'int':

                        $test_valeur = $valeur;
                        if(substr($valeur, 0, 1) == "-")
                            $test_valeur = substr($valeur, 1);

                        if(!ctype_digit($test_valeur))
                            exit("le champ " . $cle . " doit contenir un entier");

                        break;

                    default:
                        exit("erreur serveur, le type de donnée de " . $cle . " n\'est pas géré par le serveur");
                }

                if($valeur_is_string){
                    $eq_operateur = "like";
                    $end_operateur = "%";
                }
                else{
                    $eq_operateur = "=";
                    $end_operateur = "";
                }

                if($first){
                    $request = $request . " where " . $cle . " " . $eq_operateur . " '" . $valeur . $end_operateur . "'";
                    $first = false;
                }
                else{
                    $request = $request . " and " . $cle . " " . $eq_operateur . " '" . $valeur . $end_operateur . "'";
                }
            }
        }
    }

    $request = $request . ";"; 

    $executable = $bdd->prepare($request);

    $executable->execute();

    $result = $executable->fetchAll();

    if(count($result) == 0)
        echo "Pas de résultats </br>";

    else{
        echo "Voici le résultat de la requête: </br></br>";

        echo '<table>';

        print_key_line($result[0]);

        foreach($result as $data)
        {
            print_value_line($data);
        }    

        echo '</table>';
    }

    echo '</br>';
    
    get_body_return_button('page_a.php');

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

