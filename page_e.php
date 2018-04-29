<?php 

session_start();

$_SESSION['lastVisited'] = $_SERVER['REQUEST_URI'];

include 'overlay.php';
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

input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}

.form {
    position: :absolute;
    left : 100px;
    width: 60%;
    border-radius: 5px;
    padding: 20px;
}

p{
    padding-left: 20px;
}

.newInstitution {
    display: none;
}


</style>
</head>
<body>
EOT;

get_body_overlay();

begin_main();

    function table_inexistante() {
        global $ajouts_permis, $table_courante;
        $echo = "Cette page permet normalement d'ajouter un tuple dans la table " . $table_courante . ", dont les colonnes doivent être:</br>";
        $premier = true;
        foreach ($ajouts_permis[$table_courante] as $nom_colonne) {
            if($premier) {
                $echo .= $nom_colonne;
                $premier = false;
            } else {
                $echo .= " ; " . $nom_colonne;
            }
        }
        $echo .= "</br>Mais aucune table de ce type n'existe actuellement.";
        echo $echo;
        exit(1);
    }

    /*Cette fonction retourne un bloc de code HTML représentant un input de nom $nom.
    * Si la colonne $nom fais référence à une autre table, alors l'input sera une liste de toutes les valeurs existant dans la table référencée.
    * Si le nom de l'input contient "sexe" (insensible à la casse), alors l'input sera une liste de peux possibilités: M ou F.
    * Si le nom de l'input contient "date" (insensible à la casse), alors l'input sera une date.
    * Sinon, l'input sera simplement un bloc de texte classique.
    * L'input appartiendra à la classe $classe, ainsi que son label associé.
    * Si $nom contient des underscores, ils seront remplacés par des espaces pour l'affichage à l'écran.
    * De même, une majuscule est ajoutée au début de $nom lors de l'affichage.*/
    function ajoute_input($nom, $nom_table, $classe, &$tableau_pour_recherche) {
        global $bdd;
        $clé_étrangères = $GLOBALS["clé_étrangères_par_table"][$nom_table];

        $nom_affichage = str_replace("_", " ", $nom);
        $nom_affichage = strtoupper(substr($nom_affichage, 0, 1)) . substr($nom_affichage, 1);

        //réaffiche la dernière valeur de l'input
        if(isset($_POST[$nom])) {
            $valeur_initiale = $_POST[$nom];
        } else {
            $valeur_initiale = "";
        }

        $ret = "<label for='" . $nom . "' class='" . $classe . "'>" . $nom_affichage . "</label>";
        if (array_key_exists($nom, $clé_étrangères)) {
            $premier = true;
            $tableau_pour_recherche = "[";

            $résultat = execute_requête_string($bdd, "SELECT " . $clé_étrangères[$nom][1] . " FROM " . $clé_étrangères[$nom][0], null);
            $ret .= "<select id='" . $nom . "' name='" . $nom . "' class='" . $classe . "' onclick='recherche_liste(\"" . $nom . "\", " . $GLOBALS['INDEX_RECHERCHE_TABLEAUX'] . ")'>";
            $GLOBALS['INDEX_RECHERCHE_TABLEAUX']++;
            foreach ($résultat as $tuple) {
                if ($premier) {
                    $tableau_pour_recherche .= "'<option value=" . str_replace("'", "#apostrophe", $tuple[0]) . ">" . str_replace("'", "#apostrophe", $tuple[0]) . "</option>'";
                    $premier = false;
                } else {
                    $tableau_pour_recherche .= ", '<option value=" . str_replace("'", "#apostrophe", $tuple[0]) . ">" . str_replace("'", "#apostrophe", $tuple[0]) . "</option>'";
                }

                $ret .= "<option value='" . $tuple[0] . "'>" . $tuple[0] . "</option>";
            }
            $tableau_pour_recherche .= "]";

            $ret .= "<script>document.getElementById('" . $nom . "').value='" . $valeur_initiale . "'</script>";
            $ret .= "</select>";
            $ret .= "Cliquez sur la liste pour n'afficher que les éléments qui contiennent la chaîne de caractère présente dans le champ ci-dessous.</br>";
            $ret .= "<input type='text' id='" . $nom . "_champ_recherche' class='" . $classe ."'>";
        } elseif (stristr($nom, "sexe") != false) {
            $ret .= "<select id='" . $nom . "' name='" . $nom . "' class='" . $classe . "'>";
            $ret .= "<option value='M'>M</option>";
            $ret .= "<option value='F'>F</option>";
            $ret .= "<script>document.getElementById('" . $nom . "').value='" . $valeur_initiale . "'</script>";
            $ret .= "</select>";
        } elseif (stristr($nom, "date") != false) {
            $ret .= "<input type='date' id='" . $nom . "' name='" . $nom . "' value='" . $valeur_initiale . "' class='" . $classe . "'>";
        }
        else {
            $ret .= "<input type='text' id='" . $nom . "' name='" . $nom . "' placeholder='" . $nom_affichage . "...' value='" . $valeur_initiale . "' class='" . $classe . "'>";
        }

        return $ret;
    }

    try
    {
        $bdd = new PDO(get_pdo_path(), $_SESSION['uname'], $_SESSION['password']);
    }
        catch(Exception $e)
    {
        header('Location: connexion.php');
    }

    $ajouts_permis = array("Animal" => array("nom_scientifique", "n_puce", "taille", "sexe", "date_naissance", "n_enclos"),
                           "Institution" => array("nom", "rue", "code_postal", "pays"),
                           "Provenance" => array("nom_scientifique", "n_puce", "nom_institution"));

    $tables_possibles = array("Animal", "Institution", "Provenance");

    $colonnes_par_table = array();
    $clé_étrangères_par_table = array();
    foreach ($tables_possibles as $key => $table_courante) {
        $colonnes_tmp = execute_requête_string($bdd, "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table_courante . "' AND table_schema='" . get_dbname() . "'", null);
        if (count($colonnes_tmp) == 0) {
            table_inexistante();
        }

        $i = 0;
        $colonnes = array();
        foreach ($colonnes_tmp as $valeur) {
          $colonnes[$i] = $valeur['column_name'];
          $i++;
        }
        $colonnes_par_table[$table_courante] = $colonnes;

        //Si les tableaux $colonnes et $ajouts_permis[$table_courante] ne sont pas identiques, i.e. si la table qu'on a trouvé n'est pas celle qu'on voudrait
        if (array_diff($colonnes, $ajouts_permis[$table_courante]) != null || array_diff($ajouts_permis[$table_courante], $colonnes) != null) {
            table_inexistante();
        }

        //Initialise $clé_étrangères: un tableau dont les clés sont les noms des colonnes de $table_courante qui sont des clés étrangères,
        //et dont les valeurs sont des tableaux contenant le nom de la table et le nom de la colonne référencée.
        $clé_étrangères = array();
        $infos_clés_étrangères = execute_requête_string($bdd, "SELECT * FROM information_schema.key_column_usage WHERE table_name = '" . $table_courante . "' AND table_schema='" . get_dbname() . "' AND REFERENCED_TABLE_NAME like '%'", null);
        foreach ($infos_clés_étrangères as $infos_clé_étrangère) {
            $clé_étrangères[$infos_clé_étrangère['COLUMN_NAME']] = array($infos_clé_étrangère['REFERENCED_TABLE_NAME'], $infos_clé_étrangère['REFERENCED_COLUMN_NAME']);
        }
        $clé_étrangères_par_table[$table_courante] = $clé_étrangères;
    }

echo <<< EOT

  <p> Vous pouvez ici ajouter un animal à la base de donnée. </p>
  <div class="form">
  <form action="action_page_e.php" method="post">
EOT;

    $INDEX_RECHERCHE_TABLEAUX = 0;
    $recherche_tableaux = "var tableaux_options = [";
    $premier = true;
    foreach ($colonnes_par_table["Animal"] as $colonne) {
        $recherche_tableaux_tmp = "";
        echo ajoute_input($colonne, "Animal", null, $recherche_tableaux_tmp);
        if($recherche_tableaux_tmp != "") {
            if($premier) {
                $recherche_tableaux .= $recherche_tableaux_tmp;
                $premier = false;
            } else {
                $recherche_tableaux .= ", " . $recherche_tableaux_tmp;
            }
        }
    }

    //réaffiche la dernière valeur de la checkbox
    if(isset($_POST["institutionCheck"])) {
        $checked = 'checked="true"';
    } else {
        $checked = "";
    }

echo '  <input type="hidden" id="avertissement_confirmé" name="avertissement_confirmé" value="faux">

        Nouvelle institution : <input type="checkbox" id="institutionCheck" name="institutionCheck" value="true" onclick="checkBoxClick()" '. $checked . '><br><br>

        <div id="institution_2_modes"></div>';

    $nouvelle_institution = "var nouvelle_institution = \"" . str_replace('"', "#guillemet", ajoute_input("nom", "Institution", null, $recherche_tableaux_tmp)) . "\"";

    //On crée une fausse référence afin d'obtenir la liste de toutes les institutions existantes
    $clé_étrangères_par_table["Institution"]["nom"] = array("Institution", "nom");

    $recherche_tableaux_tmp = "";
    $pas_nouvelle_institution = "var pas_nouvelle_institution = \"" . str_replace('"', "#guillemet", ajoute_input("nom", "Institution", null, $recherche_tableaux_tmp)) . "\"";
    $recherche_tableaux .= ", " . $recherche_tableaux_tmp . "];";

    //On supprime les balises scripts car il y en a déjà autour de la fonction
    $pas_nouvelle_institution = str_replace("<script>", "", $pas_nouvelle_institution);
    $pas_nouvelle_institution = str_replace("</script>", "", $pas_nouvelle_institution);
    unset($clé_étrangères_par_table["Institution"]["nom"]);
    //Ajout d'une première option qui correspond à aucune institution (pas de provenance)
    $pas_nouvelle_institution = stristr($pas_nouvelle_institution, "<option ", true) . "<option value=''>Aucune institution (pas de provenance)</option>" . stristr($pas_nouvelle_institution, "<option ");

    foreach ($colonnes_par_table["Institution"] as $colonne) {
        if ($colonne != "nom") {
            echo ajoute_input($colonne, "Institution", "newInstitution", $recherche_tableaux_tmp);
        }
    }

echo <<< EOT

    <input type="submit" value="Soumettre">
  </form>
</div>
EOT;

end_main();

echo <<< EOT

<script>
EOT;
    echo "\n" . $nouvelle_institution . "\n";
    echo $pas_nouvelle_institution . "\n";

    echo $recherche_tableaux;

echo <<< EOT
function checkBoxClick() {
    var affichage_institution = document.getElementById("institution_2_modes");

    var checkBox = document.getElementById("institutionCheck");

    var toChange = document.getElementsByClassName("newInstitution");

    if (checkBox.checked == true){
        affichage_institution.innerHTML = nouvelle_institution.replace(/#guillemet/g, '"');
        for(i=0; i<toChange.length; i++) {
            toChange[i].style.display = "block";
        }
    } else {
        affichage_institution.innerHTML = pas_nouvelle_institution.replace(/#guillemet/g, '"');
        for(i=0; i<toChange.length; i++) {
            toChange[i].value="";
            toChange[i].style.display = "none";
        }
    }
}

function recherche_liste(id_liste, index_tableau_options) {
    var recherche = document.getElementById(id_liste + "_champ_recherche").value;
    var liste = document.getElementById(id_liste);
    var tableau_options = tableaux_options[index_tableau_options];
    liste.innerHTML = "";

    var nom_option_tbl;
    var nom_option;
    for (var i = 0; i < tableau_options.length; i++) {
        nom_option_tbl = tableau_options[i].split(">");
        nom_option = nom_option_tbl[1];
        nom_option_tbl = nom_option.split("<");
        nom_option = nom_option_tbl[0];
        nom_option = nom_option.replace(/#apostrophe/g, "'");

        if(nom_option.includes(recherche)) {
            liste.innerHTML += tableau_options[i].replace(/#apostrophe/g, "'");
        }
    }
}

checkBoxClick();
</script>

     
</body>
</html> 
EOT;
}

else{
    header('Location: connexion.php');
}

?>


