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
            $ret .= "<select id='" . $nom . "' name='" . $nom . "' class='" . $classe . "' onclick=recherche_liste('" . $nom . "', " . $GLOBALS['INDEX_RECHERCHE_TABLEAUX'] . ")>";
            $GLOBALS['INDEX_RECHERCHE_TABLEAUX']++;
            foreach ($résultat as $tuple) {
                if ($premier) {
                    $tableau_pour_recherche .= "'<option value=" . $tuple[0] . ">" . $tuple[0] . "</option>'";
                    $premier = false;
                } else {
                    $tableau_pour_recherche .= ", '<option value=" . $tuple[0] . ">" . $tuple[0] . "</option>'";
                }

                $ret .= "<option value='" . $tuple[0] . "'>" . $tuple[0] . "</option>";
            }
            $tableau_pour_recherche .= "]";

            $ret .= "<script>document.getElementById('" . $nom . "').value='" . $valeur_initiale . "';</script>";
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

    $nouvelle_institution = "var nouvelle_institution = \"" . ajoute_input("nom", "Institution", null, $recherche_tableaux_tmp) . "\"";

    //On crée une fausse référence afin d'obtenir la liste de toutes les institutions existantes
    $clé_étrangères_par_table["Institution"]["nom"] = array("Institution", "nom");

    $recherche_tableaux_tmp = "";
    $pas_nouvelle_institution = "var pas_nouvelle_institution = \"" . ajoute_input("nom", "Institution", null, $recherche_tableaux_tmp) . "\"";
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

    echo "var test = [['a', 'b', 'c', 'd', 'e'], ['f', 'g', 'h'], ['i', 'g']];" . "\n";

    //echo $recherche_tableaux;

    //echo "var tableaux_options = [ ['<option value=Ailuropoda melanoleuca>Ailuropoda melanoleuca</option>', '<option value=Ailurus fulgens>Ailurus fulgens</option>', '<option value=Aptenodytes forsteri>Aptenodytes forsteri</option>', '<option value=Ardea cinerea>Ardea cinerea</option>', '<option value=Bubo scandiacus>Bubo scandiacus</option>', '<option value=Canis dingo>Canis dingo</option>', '<option value=Canis lupus>Canis lupus</option>', '<option value=Cervus canadensis>Cervus canadensis</option>', '<option value=Elephas maximus>Elephas maximus</option>', '<option value=Equus caballus>Equus caballus</option>', '<option value=Eunectes murinus>Eunectes murinus</option>', '<option value=Falco peregrinus>Falco peregrinus</option>', '<option value=Felis silvestris>Felis silvestris</option>', '<option value=Giraffa camelopardalis>Giraffa camelopardalis</option>', '<option value=Haliaeetus leucocephalus>Haliaeetus leucocephalus</option>', '<option value=Hippopotamus amphibius>Hippopotamus amphibius</option>', '<option value=Loxodonta africana>Loxodonta africana</option>', '<option value=Lutra lutra>Lutra lutra</option>', '<option value=Lynx rufus>Lynx rufus</option>', '<option value=Pan troglodytes>Pan troglodytes</option>', '<option value=Panthera leo>Panthera leo</option>', '<option value=Panthera pardus>Panthera pardus</option>', '<option value=Panthera tigris>Panthera tigris</option>', '<option value=Panthera uncia>Panthera uncia</option>', '<option value=Pongo pygmaeus>Pongo pygmaeus</option>', '<option value=Prunella modularis>Prunella modularis</option>', '<option value=Rangifer tarandus>Rangifer tarandus</option>', '<option value=Struthio camelus>Struthio camelus</option>', '<option value=Urocyon cinereoargenteus>Urocyon cinereoargenteus</option>', '<option value=Ursus maritimus>Ursus maritimus</option>'], ['<option value=1>1</option>', '<option value=2>2</option>', '<option value=3>3</option>', '<option value=4>4</option>', '<option value=5>5</option>', '<option value=6>6</option>', '<option value=7>7</option>', '<option value=8>8</option>', '<option value=9>9</option>', '<option value=10>10</option>', '<option value=11>11</option>', '<option value=12>12</option>', '<option value=13>13</option>', '<option value=14>14</option>', '<option value=15>15</option>', '<option value=16>16</option>', '<option value=17>17</option>', '<option value=18>18</option>', '<option value=19>19</option>', '<option value=20>20</option>', '<option value=21>21</option>', '<option value=22>22</option>', '<option value=23>23</option>', '<option value=24>24</option>', '<option value=25>25</option>', '<option value=26>26</option>', '<option value=27>27</option>', '<option value=28>28</option>', '<option value=29>29</option>', '<option value=30>30</option>', '<option value=31>31</option>', '<option value=32>32</option>', '<option value=33>33</option>', '<option value=34>34</option>', '<option value=35>35</option>', '<option value=36>36</option>', '<option value=37>37</option>', '<option value=38>38</option>', '<option value=39>39</option>', '<option value=40>40</option>', '<option value=41>41</option>', '<option value=42>42</option>', '<option value=43>43</option>', '<option value=44>44</option>', '<option value=45>45</option>', '<option value=46>46</option>', '<option value=47>47</option>', '<option value=48>48</option>', '<option value=49>49</option>', '<option value=50>50</option>'], ['<option value=Australia Natural Reserve>Australia Natural Reserve</option>', '<option value=Bifengxia Panda Base>Bifengxia Panda Base</option>', '<option value=Institut Zoologique de Stockholm>Institut Zoologique de Stockholm</option>', '<option value=Pairi Daiza>Pairi Daiza</option>', '<option value=Parc National des Virunga>Parc National des Virunga</option>', '<option value=Parc National du Nord-Est du Groenland>Parc National du Nord-Est du Groenland</option>', '<option value=Parc Naturel de Bolivie>Parc Naturel de Bolivie</option>', '<option value=Parc de Merlet>Parc de Merlet</option>', '<option value=Réserve du Texas>Réserve du Texas</option>', '<option value=Zoo d'Anvers>Zoo d'Anvers</option>' ] ];" . "\n";

echo <<< EOT
function checkBoxClick() {
    var affichage_institution = document.getElementById("institution_2_modes");

    var checkBox = document.getElementById("institutionCheck");

    var toChange = document.getElementsByClassName("newInstitution");

    if (checkBox.checked == true){
        affichage_institution.innerHTML = nouvelle_institution;
        for(i=0; i<toChange.length; i++) {
            toChange[i].style.display = "block";
        }
    } else {
        affichage_institution.innerHTML = pas_nouvelle_institution;
        for(i=0; i<toChange.length; i++) {
            toChange[i].value="";
            toChange[i].style.display = "none";
        }
    }
}

function recherche_liste(id_liste, index_tableau_options) {
    /*var recherche = document.getElementById(id_liste + "_champ_recherche").value;
    var liste = document.getElementById(id_liste);
    var tableau_options = tableaux_options[index_tableau_options];
    console.log(tableau_options);*/
    return 4;
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


