<?php 

session_start();

if(array_key_exists('connected', $_SESSION) and $_SESSION['connected']){
    echo <<< EOT
    <!DOCTYPE html>
    <html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">

    <style>
    body {
        padding: 0;
        margin: 0;
        font-family: "Lato", sans-serif;
        background-color: BurlyWood;
    }

    .sidenav {
        height: 100%;
        width: 200px;
        position: fixed;
        z-index: 1;
        top: 200px;
        left: 0;
        background-color: #111;
        padding-top: 20px;
    }

    .hoverable a {
        padding: 6px 6px 6px 32px;

        text-decoration: none;
        font-size: 25px;
        color: #818181;
    }

    .main {
        margin-left: 210px;
        margin-top: 210px;
    }

    .header {
        position: fixed;
        top :0;
        height : 200px;
        width: 100%;
        z-index: 1;
    }

    @media screen and (max-height: 450px) {
      .sidenav {padding-top: 15px;}
      .sidenav a {font-size: 18px;}
    }

    .dropdown div{
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        left: 200px;
        top : 60px;
        background-color: #111;
        min-width: 160px;
        z-index: 0;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

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

    .hoverable:hover{
        background-color: #818181;
    }

    .hoverable:hover .link{
        color: #F0F8FF;
    }

    .hoverable2:hover{
        background-color: #818181;
    }

    .hoverable2:hover a{
        color: #F0F8FF;
    }

    .hoverable2 a{
        padding-left: 10px;
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

    input[type=submit]:hover {
        background-color: #45a049;
    }

    input[type=button] {
        display: block;
        width: 50%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 0 auto;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    a{
        text-decoration: none;
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

    <div class="header">
          <img src="ccs_banner.png" width="100%" height="100%">
    </div>

    <div class="sidenav">
        <ul >
            <li class="hoverable">
                    <a href="accueil.html" class="link">Acceuil</a>
            </li>
            <li class="dropdown hoverable"> 
                <a href="#" class="link" >Services</a>
                <ul class="dropdown-content">
                    <li class="hoverable2">
                        <a href="page_a.html" class="link2">Question a</a>
                    </li>
                    <li class="hoverable2">
                        <a href="page_b.html" class="link2">Question b</a>
                    </li>
                    <li class="hoverable2">
                        <a href="page_c.html" class="link2">Question c</a>
                    </li>
                    <li class="hoverable2">
                        <a href="page_d.html" class="link2">Question d</a>
                    </li>
                    <li class="hoverable2">
                        <a href="page_e.html" class="link2">Question e</a>
                    </li>

                </ul>   

            </li>
            <li class="hoverable">
                <a href="credits.html" class="link">Crédits</a>
            </li>
        </ul>
    </div>

    <div class="main">
EOT;

    function invalid_request()
    {
         exit("requête invalide, veuillez utiliser le formulaire de la page_a afin de faire les requêtes et ne pas envoyer vos propres requêtes au serveur.");
    }

    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=zoo;charset=utf8', 'root', '');
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

                        $format = '%d/%m/%Y %H:%M:%S';
                        if(! strptime($valeur, $format))
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

    $resultat = $executable->fetchAll();

    if(count($resultat) == 0)
        exit("Pas de résultats </br>");

    echo "Voici le résultat de la requête: </br>";

    $i = 1;
    foreach($resultat as $donnees){
        echo "</br>";
        if($i == 1){
            echo "1er resultat: </br>";
        }
        else{
            echo $i . " ème resultat: </br>";
        }
        echo "</br>";
        foreach($donnees as $champ => $valeur){
            if(is_string($champ))
                echo $champ . " = " . $valeur . "</br>";
        }
        $i++;
    }
    echo <<< EOT
    </br>
    <a href="page_a.html"> <input type="button" value="Faire une nouvelle requête"> </a>

    </div>
    </div>
    </body>
    </html> 
EOT;
}

else{
    header('Location: connexion.php');
}

?>

