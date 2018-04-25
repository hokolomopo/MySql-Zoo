<?php 

$dbName = "group24";
$dbUser = "group24";
$dbPassword = "8g7F9dhUCX";

function get_pdo_path()
{
	return "mysql:host=localhost;dbname=" . $GLOBALS['dbName'] . ";charset=utf8";
}

function get_pdo_user()
{
	return $GLOBALS['dbUser'];
}

function get_pdo_password()
{
	return $GLOBALS['dbPassword'];
}

?>