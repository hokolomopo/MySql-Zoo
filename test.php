<?php

try

{
    $bdd = new PDO('mysql:host=localhost;dbname=zoo;charset=utf8', 'root', '');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

$reponse = $bdd->query('select * from Animal');

$donnees = $reponse->fetch();

echo $donnees['taille'];
?>