<?php
$server = 'auth.local';
$username = 'root';
$password = '';
$database = 'auth';
try
{
	$conn = new PDO("mysql:host=$server;dbname=$database;",$username,$password);
}
catch(PDOException $e)
{
	die("Connection failed: " . $e->getMessage());
}

?>
