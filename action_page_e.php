<?php

try

{
    $bdd = new PDO('mysql:host=localhost;dbname=zoo;charset=utf8', 'root', '');
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

if($_POST['n_puce'] <= 0) {
	echo "Le numéro de puce ne peut pas être négatif.";
	return;
}

if($_POST['taille'] <= 0) {
	echo "La taille ne peut pas être négative.";
	return;
}

if($_POST['sexe'] != 'M' && $_POST['sexe'] != 'F') {
	echo "Le sexe n'est pas valide.";
	return;
}

if($_POST['n_enclos'] <= 0) {
	echo "Le numéro de l'enclos ne peut pas être négatif.";
	return;
}

$executable = $bdd->prepare(file_get_contents('vérifie_climat.sql'));
$executable->execute(array(':n_enclos' => $_POST['n_enclos'], ':nom_scientifique' => $_POST['nom_scientifique']));
$fetch_résultat = $executable->fetch();

$bon_climat = $fetch_résultat['bon_climat'];
if ($bon_climat == 0) {
	echo "L'enclos que vous avez choisi n'est pas adapté pour cette espèce";
}


#$executable = $bdd->prepare(file_get_contents('vérifie_institution.sql'));
#$executable->execute(array('nom_institution' => $_POST['nom']));
#$bonne_institution = $executable->fetch();
#if ($bonne_institution == 0) {
#	echo "L'institution que vous avez choisi n'existe pas, veuillez entrer ses coordonnées.";
#	return;
#}

$executable = $bdd->prepare(file_get_contents('ajoute_animal.sql'));
$executable->execute(array(':nom_scientifique' => $_POST['nom_scientifique'], ':n_puce' => $_POST['n_puce'],
						   ':taille' => $_POST['taille'], ':sexe' => $_POST['sexe'],
						   ':date_naissance' => $_POST['date_naissance'], ':n_enclos' => $_POST['n_enclos']));
?>