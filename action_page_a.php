<?php 

session_start();

include 'overlay.php';
include 'return_button.php';
include 'db_connect.php';
include 'print_table.php';
include 'execute_sql.php';

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
        $bdd = new PDO(get_pdo_path(), $_SESSION['uname'], $_SESSION['password']);
    }
    catch(Exception $e)
    {
        header('Location: connexion.php');
    }

    if(!array_key_exists('table', $_POST))
        invalid_request();
        
    $requête = 'SELECT * FROM ' . $_POST['table'];
    $premier = true;

    foreach($_POST as $cle => $valeur){

        //empeche l'utilisateur de placer des balises html et donc d'exécuter du javascript
        $cle = htmlspecialchars($cle);
        $valeur = htmlspecialchars($valeur);

        if($cle != 'table'){

            $info = execute_sql_classique($bdd, 'get_db_information.sql', array(':table' => $_POST['table'], ':column' => $cle));

            $nb_elem = count($info);

            if($nb_elem == 0)
                invalid_request();

            if($valeur != null){

                //Vérifie la validité des données
                $type = $info[0]['data_type'];
                $valeur_is_string = false;
                switch($type){

                    //le code du case varchar sera exécuté dans le case char
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
                        exit("erreur serveur, le type de donnée de " . $cle . " n'est pas géré par le serveur");
                }

                if($valeur_is_string){
                    $eq_operateur = "like";
                    $opérateur_de_début = "%";
                    $opérateur_de_fin = "%";
                }
                else{
                    $eq_operateur = "=";
                    $opérateur_de_début = "";
                    $opérateur_de_fin = "";
                }

                if($premier){
                    $requête = $requête . " where " . $cle . " " . $eq_operateur . " \"" . $opérateur_de_début . $valeur . $opérateur_de_fin . "\"";
                    $premier = false;
                }
                else{
                    $requête = $requête . " where " . $cle . " " . $eq_operateur . " \"" . $opérateur_de_début . $valeur . $opérateur_de_fin . "\"";
                }
            }
        }
    }

    $requête = $requête . ";";

    $résultat = execute_requête_string($bdd, $requête, null);

    if(count($résultat) == 0)
        echo "Pas de résultats. </br>";

    else{
        echo "Voici le résultat de la requête: </br></br>";

        affiche_tableau($résultat, $_POST['table']);
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

