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
        echo "requête invalide, veuillez utiliser le formulaire de la page_a afin de faire les requêtes et ne pas envoyer vos propres requêtes au serveur.";
        get_body_return_button_with_post($page_de_retour, $_POST);
        exit(1);
    }

    try
    {
        $bdd = new PDO(get_pdo_path(), $_SESSION['uname'], $_SESSION['password']);
    }
    catch(Exception $e)
    {
        header('Location: connexion.php');
    }

    $page_de_retour = "page_a.php";

    //Empêche l'utilisateur de placer des balises html et donc d'exécuter du javascript
    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($_POST[$key]);
    }

    if(!array_key_exists('table', $_POST))
        invalid_request();

    $colonnes_tmp = execute_requête_string($bdd, "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $_POST['table'] . "' AND table_schema='" . get_dbname() . "'", null);
    if (count($colonnes_tmp) == 0) {
        invalid_request();
    }

    $i = 0;
    $colonnes = array();
    foreach ($colonnes_tmp as $valeur) {
      $colonnes[$i] = $valeur['column_name'];
      $i++;
    }


    //Sélectionne les colonnes demandées par l'utilisateur
    $requête = "SELECT ";
    $colonne_trouvée = false;
    foreach ($colonnes as $index => $nom_colonne) {
        if (isset($_POST[$nom_colonne . "_cb"])) {
            //il ne faut pas précéder le nom de la colonne par une virgule si c'est la première colonne
            if($colonne_trouvée) {
                $requête .= ", " . $nom_colonne;
            } else {
                $requête .= $nom_colonne;
                $colonne_trouvée = true;
            }
        //C'est utile pour garder en mémoire l'état précédent de la checkbox, cf la fonction ajout_input de page_a.php.
        } else {
            $_POST[$nom_colonne . "_cb"] = "false";
        }
    }

    $requête .= " FROM " . $_POST['table'];

    if(!$colonne_trouvée) {
        echo "Vous n'avez sélectionné aucune colonne.";
        get_body_return_button_with_post($page_de_retour, $_POST);
        exit(1);
    }

    $premier = true;
    $contraintes = "";

    foreach($colonnes as $nom_colonne){

        if(!isset($_POST[$nom_colonne])) {
            invalid_request();
        }

        $info = execute_sql_classique($bdd, 'get_db_information.sql', array(':table' => $_POST['table'], ':column' => $nom_colonne));

        if($_POST[$nom_colonne] != null){

            //Vérifie la validité des données
            $type = $info[0]['data_type'];
            $valeur_is_string = false;
            switch($type){

                //le code du case varchar sera exécuté dans le case char
                case 'char':
                case 'varchar':

                    if(strlen($_POST[$nom_colonne]) > $info[0]['character_maximum_length'])
                    {
                        echo "le champ " . $nom_colonne . "doit contenir " . $info[0]['character_maximum_length'] . " caractères maximum.";
                        get_body_return_button_with_post($page_de_retour, $_POST);
                        exit(1);
                    }

                    $valeur_is_string = true;

                    break;

                case 'datetime':
                    
                    if (!(preg_match('#^([0-9]{4}).([0-9]{2}).([0-9]{2})$#', $_POST[$nom_colonne], $date_tableau) == 1 && checkdate($date_tableau[2], $date_tableau[3], $date_tableau[1])))
                    {
                        echo "le format de " . $nom_colonne . " ne correspond pas au format attendu par le serveur.";
                        get_body_return_button_with_post($page_de_retour, $_POST);
                        exit(1);
                    }

                    break;

                case 'smallint':
                case 'int':

                    $test_valeur = $_POST[$nom_colonne];
                    if(substr($_POST[$nom_colonne], 0, 1) == "-")
                        $test_valeur = substr($_POST[$nom_colonne], 1);

                    if(!ctype_digit($test_valeur))
                    {
                        echo "le champ " . $nom_colonne . " doit contenir un entier.";
                        get_body_return_button_with_post($page_de_retour, $_POST);
                        exit(1);
                    }

                    break;

                default:
                    echo "erreur serveur, le type de donnée de " . $nom_colonne . " n'est pas géré par le serveur.";
                    get_body_return_button_with_post($page_de_retour, $_POST);
                    exit(1);

                    break;
            }

            if($valeur_is_string){
                $eq_operateur = "like";
                $opérateur_de_début = "\"%";
                $opérateur_de_fin = "%\"";
            }
            else{
                $eq_operateur = "=";
                $opérateur_de_début = "";
                $opérateur_de_fin = "";
            }

            if($premier){
                $contraintes .= $nom_colonne . " " . $eq_operateur . " " . $opérateur_de_début . $_POST[$nom_colonne] . $opérateur_de_fin;
                $requête .= " where " . $nom_colonne . " " . $eq_operateur . " " . $opérateur_de_début . $_POST[$nom_colonne] . $opérateur_de_fin;
                $premier = false;
            }
            else{
                $contraintes .= " ; " . $nom_colonne . " " . $eq_operateur . " " . $opérateur_de_début . $_POST[$nom_colonne] . $opérateur_de_fin;
                $requête .= " and " . $nom_colonne . " " . $eq_operateur . " " . $opérateur_de_début . $_POST[$nom_colonne] . $opérateur_de_fin;
            }
        }
    }

    $requête = $requête . ";";

    $résultat = execute_requête_string($bdd, $requête, null);

    if(count($résultat) == 0)
        echo "Pas de résultats. </br>";

    else{
        $titre_tableau = "Liste des tuples de la table " . $_POST['table'];
        if($contraintes != "") {
            $contraintes = str_replace("like", "contient", $contraintes);
            $contraintes = str_replace("%", "", $contraintes);
            $contraintes = str_replace("\"", "", $contraintes);
            $contraintes = str_replace("=", "vaut", $contraintes);
            $titre_tableau .= ", avec les contraintes suivantes:</br>";
            $titre_tableau .= $contraintes;
        }

        affiche_tableau($résultat, $titre_tableau);
    }

    echo '</br>';
    
    get_body_return_button_with_post($page_de_retour, $_POST);

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

