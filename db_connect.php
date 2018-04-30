<?php 

$dbName = "zoo";

function get_pdo_path()
{
	return "mysql:host=localhost;dbname=" . $GLOBALS['dbName'] . ";charset=utf8";
}

function get_dbname()
{
	return $GLOBALS['dbName'];
}

?>