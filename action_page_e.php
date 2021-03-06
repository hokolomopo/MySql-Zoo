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
    input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}  

input[type=date], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}  

input.post_confirm_button {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input.post_confirm_button:hover {
    background-color: #45a049;
}

.form {
    position: :absolute;
    left : 100px;
    width: 60%;
    border-radius: 5px;
    padding: 20px;
}
    </style>
    </head>
    <body>
EOT;

    corps_fond();

    debut_main();

    $page_de_retour = 'page_e.php';

    /*Cette fonction vérifie que val ne soit pas vide et qu'elle soit comprise entre les bornes min et max.
    * Si c'est le cas, elle ne fait rien; sinon, elle affiche un message d'erreur en nommant val par nom.*/
    function vérifie_conditions_intégrité($val, $min, $max, $nom) {
        if ($val == "" || $val < $min || $val > $max) {
            echo $nom . " doit appartenir à l'intervalle [" . $min . " ; " . $max . "].";
            bouton_retour_avec_post($GLOBALS['page_de_retour'], $_POST);
            exit(1);
        }
    }

    try
    {
        $bdd = new PDO(adresse_pdo(), $_SESSION['uname'], $_SESSION['password']);
    }
    catch (Exception $e)
    {
        header('Location: connexion.php');
    }

    //Les variables sont set même si l'utilisateur n'a rien n'écrit dans le formulaire
    if (!isset($_POST['avertissement_confirmé']) || !isset($_POST['nom_scientifique']) || !isset($_POST['n_puce']) ||
        !isset($_POST['taille']) || !isset($_POST['sexe']) || !isset($_POST['date_naissance']) || !isset($_POST['n_enclos']) ||
        !isset($_POST['nom']) || !isset($_POST['rue']) || !isset($_POST['code_postal']) ||
        !isset($_POST['pays'])) {
        echo "Veuillez utiliser le formulaire de la page e afin d'effectuer un ajout d'animal, et compléter les champs requis.</br>";
        bouton_retour_avec_post($page_de_retour, $_POST);
        exit(1);
    }

    //Empêche l'utilisateur de placer des balises html et donc d'exécuter du javascript
    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($_POST[$key]);
    }

    //vérifie les conditions d'intégrité des valeurs entrées
    vérifie_conditions_intégrité($_POST['n_puce'], 0, 65535, "Le numéro de puce");
    vérifie_conditions_intégrité($_POST['taille'], 1, 2147483647, "La taille");

    if($_POST['sexe'] != 'M' && $_POST['sexe'] != 'F') {
        echo "Le sexe n'est pas valide, il doit être indiqué par M ou F.";
        bouton_retour_avec_post($page_de_retour, $_POST);
        exit(1);
    }

    if (!(preg_match('#^([0-9]{4}).([0-9]{2}).([0-9]{2})$#', $_POST['date_naissance'], $date_tableau) == 1 && checkdate($date_tableau[2], $date_tableau[3], $date_tableau[1]))) {
        echo "La date doit être fournie au format aaaa*mm*jj où les * peuvent être remplacées par n'importe quel caractère, et être valide.</br>";
        bouton_retour_avec_post($page_de_retour, $_POST);
        exit(1);
    }
    //Grâce au format année-mois-jour, on peut savoir si une date précède une autre simplement en comparant les chaînes de caractères.
    vérifie_conditions_intégrité($_POST['date_naissance'], "1900-01-01", "2018-12-31", "La date");
    $date = $date_tableau[3] . "/" . $date_tableau[2] ."/" . $date_tableau[1];

    //vérifie que les références vers d'autres tables sont correctes
    if (! (execute_vérification_existence($bdd, 'e_vérifie_nom_scientifique.sql', array(':nom_scientifique' => $_POST['nom_scientifique']))) ) {
        echo "L'espèce doit appartenir à la base de donnée.";
        bouton_retour_avec_post($page_de_retour, $_POST);
        exit(1);
    }

    if (! (execute_vérification_existence($bdd, 'e_vérifie_enclos.sql', array(':n_enclos' => $_POST['n_enclos']))) ) {
        echo "L'enclos doit exister.";
        bouton_retour_avec_post($page_de_retour, $_POST);
        exit(1);
    }

    if (execute_vérification_existence($bdd, 'e_animal_existe_déjà.sql', array(':nom_scientifique' => $_POST['nom_scientifique'], ':n_puce' => $_POST['n_puce']))) {
        echo "Cet animal existe déjà, veuillez choisir un autre numéro de puce.</br>";
        echo "Voici la liste, triée dans l'ordre croissant, des numéros de puce déjà utilisés pour l'espèce ";
        echo $_POST['nom_scientifique'];
        echo ":</br>";

        $animaux = execute_sql_classique($bdd, 'e_n_puce.sql', array(':nom_scientifique' => $_POST['nom_scientifique']));

        affiche_tableau($animaux, "Numéros de puce");

        bouton_retour_avec_post($page_de_retour, $_POST);
        exit(1);
    }


    if($_POST['avertissement_confirmé'] == "faux") {
        //Avertissement si l'enclos n'est pas adapté
        if (! (execute_vérification_existence($bdd, 'e_vérifie_climat.sql', array(':n_enclos' => $_POST['n_enclos'], ':nom_scientifique' => $_POST['nom_scientifique']))) ) {
            echo "L'enclos que vous avez choisi n'est pas adapté pour cette espèce, voulez-vous quand même ajouter l'animal?</br>";
            echo "Voici un récapitulatif des informations que vous avez entrées: </br></br>";

            if (isset($_POST['institutionCheck'])) {
                $checked = "checked=\"true\"";
            } else {
                $checked = "";
            }

            echo "
            <div style=\"width: 60%;\">
                <form action=\"action_page_e.php\" method=\"post\">

                    <label for=\"nom_scientifique\">Nom scientifique</label>
                    <input type=\"text\" id=\"nom_scientifique\" name=\"nom_scientifique\"
                           value=\"".$_POST['nom_scientifique']."\" readonly=\"true\">

                    <label for=\"n_puce\">Numéro de puce</label>
                    <input type=\"text\" id=\"n_puce\" name=\"n_puce\"
                           value=\"".$_POST['n_puce']."\" readonly=\"true\">

                    <label for=\"taille\">Taille</label>
                    <input type=\"text\" id=\"taille\" name=\"taille\"
                           value=\"".$_POST['taille']."\" readonly=\"true\">

                    <label for=\"sexe\">Sexe</label>
                    <input type=\"text\" id=\"sexe\" name=\"sexe\"
                           value=\"".$_POST['sexe']."\" readonly=\"true\">

                    <label for=\"date_naissance\">Date de naissance</label>
                    <input type=\"text\" id=\"date_naissance\" name=\"date_naissance\"
                           value=\"".$_POST['date_naissance']."\" readonly=\"true\">

                    <label for=\"n_enclos\">Numéro de l'enclos</label>
                    <input type=\"text\" id=\"n_enclos\" name=\"n_enclos\"
                           value=\"".$_POST['n_enclos']."\" readonly=\"true\">

                    <input type=\"hidden\" id=\"avertissement_confirmé\" name=\"avertissement_confirmé\" value=\"vrai\">

                    Nouvelle institution : 
                    <input type=\"checkbox\" id=\"institutionCheck\" name=\"institutionCheck\" value=\"true\"
                           onclick=\"return false;\" ".$checked."></br>

                    <label for=\"nom\">Nom</label>
                    <input type=\"text\" id=\"nom\" name=\"nom\"
                           value=\"".$_POST['nom']."\" readonly=\"true\">

                    <label for=\"rue\">Rue</label>
                    <input type=\"text\" id=\"rue\" name=\"rue\"
                           value=\"".$_POST['rue']."\" readonly=\"true\">

                    <label for=\"code_postal\">Code postal</label>
                    <input type=\"text\" id=\"code_postal\" name=\"code_postal\"
                           value=\"".$_POST['code_postal']."\" readonly=\"true\">

                    <label for=\"pays\">Pays</label>
                    <input type=\"text\" id=\"pays\" name=\"pays\"
                           value=\"".$_POST['pays']."\" readonly=\"true\">

                    <input type=\"submit\" class=\"post_confirm_button\" value=\"Ajouter quand même\">
                </form>
            </div>
            ";
            bouton_retour_avec_post($page_de_retour, $_POST);
            exit(1);
        }
    }

    $ajouter_institution = false;
    $ajouter_provenance = false;

    $institution_tmp = execute_sql_classique($bdd, 'e_institution_existe_déjà.sql', array(':nom' => $_POST['nom']));
    $institution = $institution_tmp[0];

    //Il faut ajouter une nouvelle institution
    if (isset($_POST['institutionCheck']) == 1) {
        if($_POST['nom'] == "") {
            echo "Le nom de l'institution doit contenir au moins une lettre.</br>";
            bouton_retour_avec_post($page_de_retour, $_POST);
            exit(1);
        }

        if ($institution['existe'] == 0) {
            if ($_POST['rue'] == "") {
                echo "La rue de l'institution est manquante";
                bouton_retour_avec_post($page_de_retour, $_POST);
                exit(1);
            }

            vérifie_conditions_intégrité($_POST['code_postal'], 1, 999999999, "Le code postal");

            if($_POST['pays'] == "") {
                echo "Le pays de l'institution est manquant";
                bouton_retour_avec_post($page_de_retour, $_POST);
                exit(1);
            }
            $ajouter_institution = true;
            $ajouter_provenance = true;

        } else {
            if ($institution['rue'] == $_POST['rue'] && $institution['code_postal'] == $_POST['code_postal'] && $institution['pays'] == $_POST['pays']) {
                echo "Avertissement: l'institution que vous avez entrée existait déjà, elle n'a donc pas été ajoutée</br>";
                $ajouter_provenance = true;
            } else {
                echo "Une autre institution avec le même nom existe déjà, impossible d'ajouter cette institution";
                bouton_retour_avec_post($page_de_retour, $_POST);
                exit(1);
            }
        }
    } else {
        if($_POST['nom'] != "") {
            if($institution['existe'] == 0) {
                echo "L'institution de provenance n'existe pas";
                bouton_retour_avec_post($page_de_retour, $_POST);
                exit(1);
            } else {
                $ajouter_provenance = true;
            }
        }
    }

    execute_sql_insert($bdd, 'e_ajoute_animal.sql', array(':nom_scientifique' => $_POST['nom_scientifique'], ':n_puce' => $_POST['n_puce'],
                                                                 ':taille' => $_POST['taille'], ':sexe' => $_POST['sexe'],
                                                                 ':date_naissance' => $date, ':n_enclos' => $_POST['n_enclos']));

    if ($ajouter_institution) {
        execute_sql_insert($bdd, 'e_ajoute_institution.sql', array(':nom' => $_POST['nom'], ':rue' => $_POST['rue'], ':code_postal' => $_POST['code_postal'],
                                                                          ':pays' => $_POST['pays']));
    }

    if ($ajouter_provenance) {
         execute_sql_insert($bdd, 'e_ajoute_provenance.sql', array(':nom_scientifique' => $_POST['nom_scientifique'], ':n_puce' => $_POST['n_puce'], ':nom_institution' => $_POST['nom']));
    }

    echo "L'animal a été ajouté avec succès !</br>";
    echo "Voici un récapitulatif :</br>";
    unset($_POST["avertissement_confirmé"]);
    unset($_POST["institutionCheck"]);
    foreach ($_POST as $key => $value) {
        if ($value == "") {
            unset($_POST[$key]);
        }
    }

    $à_afficher = array(0 => $_POST);

    affiche_tableau($à_afficher, "Récapitulatif de l'ajout");

} else {
    header('Location: connexion.php');
}

    bouton_retour_avec_post($page_de_retour, $_POST);

    fin_main();
    
    echo <<< EOT
    </body>
    </html>
EOT;

?>