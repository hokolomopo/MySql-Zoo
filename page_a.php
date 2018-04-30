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

style_fond();

echo <<< EOT

ul
{
    margin: 0;
    padding: 0;
}

li
{
    list-style:none;
    padding-bottom: 5px;
    padding-top: 5px;
}

input[type=text], select {
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

input[type=date], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
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

</style>
</head>
<body>
EOT;

corps_fond();

debut_main();

/*Cette fonction doit être utilisée à l'intérieur d'un <select>. Si c'est le cas, elle ajoutera une option à cette liste.
* L'argument est le nom de l'option.*/
function ajoute_option_table($nom_option, $numéro_index) {
  echo "<option value='" . $nom_option . "' onclick=\"afficheContraintes(" . $numéro_index . ")\">" . $nom_option . "</option>";
}

/*Cette fonction renvoie une chaîne de caractère représentant une contrainte (bloc de code HTML).
* Une contrainte consiste en un input de type texte et de nom $nom, et en une checkbox (cochée par défaut) associée.
* Si $nom contient des underscores, ils seront remplacés par des espaces pour l'affichage à l'écran.
* De même, une majuscule est ajoutée au début de $nom lors de l'affichage.*/
function ajoute_input($nom) {
  $nom_affichage = str_replace("_", " ", $nom);
  $nom_affichage = strtoupper(substr($nom_affichage, 0, 1)) . substr($nom_affichage, 1);

  //réaffiche la dernière valeur de l'input
  if(isset($_POST[$nom])) {
    $valeur_initiale = $_POST[$nom];
  } else {
    $valeur_initiale = "";
  }

  //réaffiche le dernier état de la checkbox
  if(isset($_POST[$nom . "_cb"])) {
    if ($_POST[$nom . "_cb"] == "false") {
      $checked = "";
    } else {
      $checked = "checked='true'";
    }
  //La valeur par défaut d'une checkbox est cochée
  } else {
    $checked = "checked='true'";
  }

  $ret = "<li><label for='" . $nom . "'>" . $nom_affichage . "</label>";
  $ret .= "<input type='checkbox' id='" . $nom . "_cb' name='" . $nom . "_cb' value='true' " . $checked . ">";
  $ret .= "<input type='text' id='" . $nom . "' name='" . $nom . "' placeholder='" . $nom_affichage . "...' value=#guillemet" . $valeur_initiale . "#guillemet></li>";
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

  //Initialise $noms_tables: un tableau contenant le nom de toutes les tables présentes dans la base de donnée.
  //L'ordre des tables dans $noms_tables est notre référence, et on le respectera rigoureusement afin de pouvoir ensuite comparer les index des différents tableaux.
  $noms_tables_tmp = execute_requête_string($bdd, "SELECT DISTINCT table_name FROM information_schema.columns WHERE table_schema=\"" . get_dbname() . "\"", null);

  $noms_tables = array();
  $i = 0;
  foreach ($noms_tables_tmp as $valeur) {
    $noms_tables[$i] = $valeur['table_name'];
    $i++;
  }


  //Initialise $tableau_contraintes: un tableau ayant comme clé le nom des tables et comme valeur une chaîne de caractère représentant le code HTML des <input> associés à cette table.
  $j = 0;
  $tableau_contraintes = array();
  //$indexs prend comme clé le nom d'une table, et renvoie l'index associé à cette table dans $tableau_contraintes_JS
  $indexs = array();
  foreach ($noms_tables as $table) {
    $colonnes_tmp = execute_requête_string($bdd, "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "' AND table_schema='" . get_dbname() . "'", null);

    $i = 0;
    $colonnes = array();
    foreach ($colonnes_tmp as $valeur) {
      $colonnes[$i] = $valeur['column_name'];
      $i++;
    }

    $contrainte = "";
    foreach ($colonnes as $colonne) {
      $contrainte .= ajoute_input($colonne);
    }
    $indexs[$table] = $j;
    $tableau_contraintes[$j] = $contrainte;
    $j++;
  }

  //Initialise $tableau_contraintes_JS: une chaîne de caractère représentant un tableau javascript contenant la même chose que $tableau_contraintes.
  if(count($tableau_contraintes) != 0) {
    $tableau_contraintes_JS = "var à_afficher = [\"";
    for ($i = 0; $i < count($tableau_contraintes); $i++) {
      $tableau_contraintes_JS .= $tableau_contraintes[$i];

      //au dernier tour on ferme le crochet plutôt que de mettre une virgule
      if ($i == count($tableau_contraintes) - 1) {
        $tableau_contraintes_JS .= "\"];";
      } else {
        $tableau_contraintes_JS .= "\", \"";
      }
    }
  } else {
    $tableau_contraintes_JS = "";
  }

echo <<< EOT
     
<script>
EOT;
    //Ne pas regarder le code source de la page une fois le php compilé... 
    echo $tableau_contraintes_JS;

echo <<< EOT
    function afficheContraintes(table_sélectionnée) {
        document.getElementById("liste_contraintes").innerHTML = à_afficher[table_sélectionnée].replace(/#guillemet/g, '"').replace(/#apostrophe/g, "'");
    }
</script>
EOT;


//Crée une liste permettant la sélection de la table
echo <<< EOT

  <p> Vous pouvez ici choisir une table à afficher, avec éventuellement des contraintes.</br>Vous pouvez sélectionner les colonnes pour l'affichage en cliquant sur la checkbox correspondante.</br>
      Vous pouvez contraindre les valeurs d'une colonne même si vous ne l'avez pas sélectionnée.</br>
      Pour les champs textuels, les tuples affichés seront ceux contenant la valeur indiquée.</br>Pour les champs numériques, seront affichés les tuples pour lesquels l'égalité est vérifiée.</br></p>
  <div class="form">
  <form action="action_page_a.php" method="post">
    <label for="table">Table</label>
    <select id="table" name="table">
EOT;

    $i = 0;
    foreach ($noms_tables as $table) {
      ajoute_option_table($table, $i);
      $i++;
    }

    if(isset($_POST['table']) && array_key_exists($_POST['table'], $indexs)) {
      echo "<script>document.getElementById('table').value='" . $_POST['table'] . "'</script>\n";
    }

echo <<< EOT
    </select>

    <ul class="test1" id="liste_contraintes">
EOT;

    if(isset($_POST['table']) && array_key_exists($_POST['table'], $indexs)) {
        echo "<script>afficheContraintes(" . $indexs[$_POST['table']] . ")</script>";
    } else {
      if (array_key_exists(0, $tableau_contraintes)) {
        echo "<script>afficheContraintes(0)</script>";
      } else {
        echo "<script>document.getElementById('liste_contraintes').innerHTML = ''";
      }
    }

  echo <<< EOT
    </ul>

    <input type="submit" value="Soumettre">
  </form>
  </div>
EOT;

fin_main();

echo <<< EOT

</body>
</html> 
EOT;
}

else{
    header('Location: connexion.php');
}

?>





