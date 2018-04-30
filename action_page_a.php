<?php 

session_start();

include 'overlay.php';
include 'return_button.php';
include 'db_connect.php';
include 'print_table.php';
include 'execute_sql.php';

// si l'utilisateur est connectés, affiche la page
if(array_key_exists('connected', $_SESSION) and $_SESSION['connected']) {
    echo <<< EOT
    <!DOCTYPE html>
    <html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">

    <style>
EOT;
    
    //ajoute le code css de l'overlay
    get_style_overlay();

    //ajoute le code css du bouton de retour
    get_style_return_button();

    //ajoute le code css des tableaux
    get_style_table();

    echo <<< EOT

    </style>
    </head>
    <body>
EOT;

    //ajoute le code html de l'overlay
    get_body_overlay();

    //ajoute le code html constituant le debut de la partie principale 
    begin_main();

    //fonctionnement en cas de requête invalide
    function requete_invalide()
    {
        echo "requête invalide, veuillez utiliser le formulaire de la page_a afin de faire les requêtes et ne pas envoyer vos propres requêtes au serveur.";
        get_body_return_button($GLOBALS['page_de_retour']);
        exit(1);
    }

    //crée l'accès à la base de de données
    try
    {
        $bdd = new PDO(get_pdo_path(), $_SESSION['uname'], $_SESSION['password']);
    }
    //en cas d'erreur, on retourne a la page de connexion car il est probable que ce soit du aux indentifiants
    catch(Exception $e)
    {
        header('Location: connexion.php');
    }

    $page_de_retour = "page_a.php";

    //Empêche l'utilisateur de placer des balises html et donc d'exécuter du javascript
    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($_POST[$key]);
    }

    //vérifie la présence de l'information concernant la table traitée dans la requête
    if(!array_key_exists('table', $_POST))
        requete_invalide();

    //récupère le nom des différentes colonnes de la table traitée
    $colonnes_tmp = execute_requête_string($bdd, "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $_POST['table'] . "' AND table_schema='" . get_dbname() . "'", null);
    if (count($colonnes_tmp) == 0) {
        requete_invalide();
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

    //début de la construction de la requête
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
            requete_invalide();
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

                    //notifie qu'il s'agit d'un type string afin de moifier la manière de gérer l'égalité dans la requête sql
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

                //le code du case int sera exécuté dans le case smallint
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

            //ajoute la condition d'égalité dans le cas d'une string, Cette contrainte est faite de sorte que les éléments dont le début de la chaine de caractère de l'attribut correspond à la valeur recherchée mais incomplète soient reprises.
            if($valeur_is_string){
                $eq_operateur = "like";
                $opérateur_de_début = '"%';
                $opérateur_de_fin = '%"';
            }
            //ajoute la condition d'égalité dans les autre cas.
            else{
                $eq_operateur = "=";
                $opérateur_de_début = "";
                $opérateur_de_fin = "";
            }

            //cas ou c'est la première contrainte exprimée dans la requête
            if($premier){
                $contraintes .= $nom_colonne . " " . $eq_operateur . " " . $opérateur_de_début . $_POST[$nom_colonne] . $opérateur_de_fin;
                $requête .= " where " . $nom_colonne . " " . $eq_operateur . " " . $opérateur_de_début . $_POST[$nom_colonne] . $opérateur_de_fin;
                $premier = false;
            }
            //cas ou ce n'est pas la première contrainte exprimée dans la requête
            else{
                $contraintes .= " ; " . $nom_colonne . " " . $eq_operateur . " " . $opérateur_de_début . $_POST[$nom_colonne] . $opérateur_de_fin;
                $requête .= " and " . $nom_colonne . " " . $eq_operateur . " " . $opérateur_de_début . $_POST[$nom_colonne] . $opérateur_de_fin;
            }
        }
    }

    //fin de la construction de la requête
    $requête = $requête . ";";

    //exécution de la requête
    $résultat = execute_requête_string($bdd, $requête, null);

    //affichage du résultat
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

    //ajoute le code html constituant le debut de la partie principale
    end_main();

    echo <<< EOT
    </body>
    </html> 
EOT;
}

//si l'utilisateur n'est pas connecté, renvoie a la page de connexion
else{
    header('Location: connexion.php');
}

?>

