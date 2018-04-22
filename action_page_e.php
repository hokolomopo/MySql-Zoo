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
<?php

try

{
    $bdd = new PDO('mysql:host=localhost;dbname=zoo;charset=utf8', 'root', '');
}
catch (Exception $e)
{
    fwrite(STDERR, "Une erreur inattendue est survenue lors de la connexion à la base de donnée" . $e->getMessage());
    exit(1);
}

echo $_POST['avertissement_confirmé'];
echo "</br>";
echo $_POST['nom_scientifique'];
echo "</br>";
echo $_POST['n_puce'];
echo "</br>";
echo $_POST['taille'];
echo "</br>";
echo $_POST['sexe'];
echo "</br>";
echo $_POST['date_naissance'];
echo "</br>";
echo $_POST['n_enclos'];
echo "</br>";

//vérifie les conditions d'intégrité des valeurs entrées
if($_POST['n_puce'] == "" || $_POST['n_puce'] < 0 || $_POST['n_puce'] > 65535) {
	echo "Le numéro de puce doit être compris dans l'intervalle [0 ; 65 535]";
	return;
}

if($_POST['taille'] == "" || $_POST['taille'] <= 0 || $_POST['taille'] > 2147483647) {
	echo "La taille doit être comprise dans l'intervalle [1 ; 2 147 483 647] cm.";
	return;
}

if($_POST['sexe'] != 'M' && $_POST['sexe'] != 'F') {
	echo "Le sexe n'est pas valide, il doit être indiqué par M ou F.";
	return;
}

$date = $_POST['date_naissance'];
$annee = substr($date, 0, 4);
$mois = substr($date, 5, 2);
$jour = substr($date, 8, 2);
if($annee == false || $annee < 1900 || $annee > 2018 || $mois == false || $mois <= 0
|| $mois > 12 || $jour == false || $jour <= 0 || $jour > 31) {
	echo "La date doit être fournie au format aaaa*mm*jj où les * peuvent être remplacées par n'importe quel caractère,</br>";
	echo "et correspondre à une date valide dans l'intervalle [1900/01/01 ; 2018-12-31]";
	return;
}

//vérifie que les références vers d'autres tables sont correctes
$executable = $bdd->prepare(file_get_contents('e_vérifie_nom_scientifique.sql'));
$executable->execute(array(':nom_scientifique' => $_POST['nom_scientifique']));
$fetch_résultat = $executable->fetch();

$bon_nom = $fetch_résultat['bon_nom'];
if ($bon_nom == 0) {
	echo "L'espèce doit appartenir à la base de donnée.";
	return;
}

$executable = $bdd->prepare(file_get_contents('e_vérifie_enclos.sql'));
$executable->execute(array(':n_enclos' => $_POST['n_enclos']));
$fetch_résultat = $executable->fetch();

$bon_enclos = $fetch_résultat['bon_enclos'];
if ($bon_enclos == 0) {
	echo "L'enclos doit exister.";
	return;
}

$executable = $bdd->prepare(file_get_contents('e_animal_existe_déjà.sql'));
$executable->execute(array(':nom_scientifique' => $_POST['nom_scientifique'], ':n_puce' => $_POST['n_puce']));
$fetch_résultat = $executable->fetch();

$bon_animal = $fetch_résultat['bon_animal'];
if ($bon_animal != 0) {
	echo "Cet animal existe déjà, veuillez choisir un autre numéro de puce.</br>";
	echo "Voici la liste, triée dans l'ordre croissant, des numéros de puce déjà utilisés pour l'espèce ";
	echo $_POST['nom_scientifique'];
	echo ":</br>";
	$executable = $bdd->prepare(file_get_contents('e_n_puce.sql'));
	$executable->execute(array(':nom_scientifique' => $_POST['nom_scientifique']));

	$fetch_résultat = $executable->fetch();
	while($fetch_résultat) {
		echo $fetch_résultat['n_puce'];
		echo "</br>";
		$fetch_résultat = $executable->fetch();
	}
	return;
}


if($_POST['avertissement_confirmé'] == "faux") {
	//Avertissement si l'enclos n'est pas adapté
	$executable = $bdd->prepare(file_get_contents('e_vérifie_climat.sql'));
	$executable->execute(array(':n_enclos' => $_POST['n_enclos'], ':nom_scientifique' => $_POST['nom_scientifique']));
	$fetch_résultat = $executable->fetch();

	$bon_climat = $fetch_résultat['bon_climat'];
	if ($bon_climat == 0) {
		$nom_tableau = explode(" ", $_POST['nom_scientifique']);
		echo "L'enclos que vous avez choisi n'est pas adapté pour cette espèce, voulez-vous quand même ajouter l'animal?</br>";
		echo "Voici un récapitulatif des informations que vous avez entrées: </br></br>";

		echo "
		<div style=\"width: 60%;\">
			<form action=\"action_page_e.php\" method=\"post\">

			<label for=\"nom_scientifique\">Nom scientifique</label>
			<input type=\"text\" id=\"nom_scientifique\" name=\"nom_scientifique\"
			       value=\"".$_POST['nom_scientifique']."\" readonly=\"true\">

			<label for=\"n_puce\">Numéro de puce</label>
			<input type=\"text\" id=\"n_puce\" name=\"n_puce\"
			       value=".$_POST['n_puce']." readonly=\"true\">

			<label for=\"taille\">Taille</label>
			<input type=\"text\" id=\"taille\" name=\"taille\"
			       value=".$_POST['taille']." readonly=\"true\">

			<label for=\"sexe\">Sexe</label>
			<input type=\"text\" id=\"sexe\" name=\"sexe\"
			       value=".$_POST['sexe']." readonly=\"true\">

			<label for=\"date_naissance\">Date de naissance</label>
			<input type=\"text\" id=\"date_naissance\" name=\"date_naissance\"
			       value=".$_POST['date_naissance']." readonly=\"true\">

			<label for=\"n_enclos\">Numéro de l'enclos</label>
			<input type=\"text\" id=\"n_enclos\" name=\"n_enclos\"
			       value=".$_POST['n_enclos']." readonly=\"true\">

			<input type=\"text\" id=\"avertissement_confirmé\" name=\"avertissement_confirmé\"
			       value=\"vrai\" readonly=\"true\" style=\"display: none\">

			<input type=\"submit\" value=\"Ajouter quand même\">
		</div>
		";
		return;
	}
}

#$executable = $bdd->prepare(file_get_contents('e_vérifie_institution.sql'));
#$executable->execute(array('nom_institution' => $_POST['nom']));
#$bonne_institution = $executable->fetch();
#if ($bonne_institution == 0) {
#	echo "L'institution que vous avez choisi n'existe pas, veuillez entrer ses coordonnées.";
#	return;
#}

$executable = $bdd->prepare(file_get_contents('e_ajoute_animal.sql'));
$executable->execute(array(':nom_scientifique' => $_POST['nom_scientifique'], ':n_puce' => $_POST['n_puce'],
						   ':taille' => $_POST['taille'], ':sexe' => $_POST['sexe'],
						   ':date_naissance' => $_POST['date_naissance'], ':n_enclos' => $_POST['n_enclos']));
$executable->fetchAll();

?>
</div>
</body>
</html>