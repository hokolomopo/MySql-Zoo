<?php 

session_start();

include 'overlay.php';
include 'return_button.php';
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

    style_bouton_retour();

    echo <<< EOT
    .cv {
        display: table-cell;
        vertical-align: middle;
    }
    </style>
    </head>
    <body>
EOT;

    corps_fond();

    debut_main();

    /*Cette fonction traduit un pourcentage en français. L'argument ne doit pas commencer par des zéros inutiles (exemple : 04.45).
    * La précision de l'affichage en français se limitera aux deux premières décimales, avec un arrondi vers le bas.*/
    function traduction_pourcentage_français($nombre) {
        $valeurs_de_base = array("0" => "zéro", "1" => "un", "2" => "deux", "3" => "trois", "4" => "quatre", "5" => "cinq", "6" => "six", "7" => "sept", "8" => "huit", "9" => "neuf",
                                 "11" => "onze", "12" => "douze", "13" => "treize", "14" => "quatorze", "15" => "quinze", "16" => "seize", "100" => "cent");
        $dizaines = array("0" => "zéro", "1" => "dix", "2" => "vingt", "3" => "trente", "4" => "quarante", "5" => "cinquante", "6" => "soixante", "7" => "septante", "8" => "quatre-vingt", "9" => "nonante");
        $unités = array("0" => "", "1" => "-et-un", "2" => "-deux", "3" => "-trois", "4" => "-quatre", "5" => "-cinq", "6" => "-six", "7" => "-sept", "8" => "-huit", "9" => "-neuf");

        if($nombre > 100.00 || $nombre < 0.00) {
            return null;
        }
        $nombre_tableau_str = explode(".", strval($nombre));

        $ret = "";
        for ($i = 0; $i < count($nombre_tableau_str); $i++) {
            if($i == 1) {
                $ret .= " virgule ";
            }
            $à_traduire = $nombre_tableau_str[$i];
            if(array_key_exists($à_traduire, $valeurs_de_base)) {
                $traduction = $valeurs_de_base[$à_traduire];
            } else {
                $traduction = $dizaines[substr($à_traduire, 0, 1)] . $unités[substr($à_traduire, 1, 1)];
            }

            $ret .= $traduction;
        }

        return $ret . " pourcents";
    }

    echo "<center><div style='padding-top: 5%'>";

    try
    {
        $bdd = new PDO(get_pdo_path(), $_SESSION['uname'], $_SESSION['password']);
    }
    catch(Exception $e)
    {
        header('Location: connexion.php');
    }

    $page_de_retour = "page_d.php";

    $résultats = execute_sql_classique($bdd, "d_proportion.sql", null);

    $proportion = $résultats[0]['proportion'];
    $proportion = doubleval($proportion)*100;
    $proportion_fr = traduction_pourcentage_français($proportion);

    echo "<p>La proportion d'interventions qui ont été effectuées sur des animaux présents dans un enclos dont le climat ";
    echo "ne correspond pas à l'un de ceux supportés par son espèce est de</br>";
    echo $proportion_fr . " ( " . $proportion . "% )</p>";

    bouton_retour_gradient($page_de_retour, $proportion);

    fin_main();

    echo <<< EOT
    </div></center>
    </body>
    </html>
EOT;
}

else{
    header('Location: connexion.php');
}

?>