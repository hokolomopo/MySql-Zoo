<?php 

$dbName = "group24";

function adresse_pdo()
{
	return "mysql:host=localhost;dbname=" . $GLOBALS['dbName'] . ";charset=utf8";
}

function bd_nom()
{
	return $GLOBALS['dbName'];
}

?>