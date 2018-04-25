<?php

function execute_sql_classique($bdd, $nom_fichier, $tableau_arguments) {
	$executable = $bdd->prepare(file_get_contents($nom_fichier));
    $executable->execute($tableau_arguments);
    return $executable->fetchAll();
}


?>