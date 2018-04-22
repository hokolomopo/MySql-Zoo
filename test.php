<?php

try

{
    $bdd = new PDO('mysql:host=localhost;dbname=zoo;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

#$_POST['taille'];

$executable = $bdd->prepare(file_get_contents('e.sql'));

$executable->execute();

while($donnees = $executable->fetch()){
	echo '<pre>';
	print_r($donnees);
	echo '</pre>';
}
?>