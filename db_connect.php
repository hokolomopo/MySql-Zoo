<?php 

$dbName = "zoo";

function adresse_pdo()
{
	return "mysql:host=localhost;dbname=" . $GLOBALS['dbName'] . ";charset=utf8";
}

function bd_nom()
{
	return $GLOBALS['dbName'];
}

?>