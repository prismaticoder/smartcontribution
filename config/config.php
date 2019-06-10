<?php

ob_start();
session_start();

//HostName
$host = 'localhost';
//Database
$db = 'smart_contribution';
//Username
$user ='root';
//Password
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('Connection Error : '. mysqli_connect_error());
}
$GLOBALS['connection'] = $conn;

// define site path
define('DIR','http://localhost/smartcontribution/');

include('functions.php')

?>