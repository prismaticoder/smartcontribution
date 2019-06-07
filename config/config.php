<?php

ob_start();
session_start();

//HostName
define($host, 'localhost');
//Database
define($db, 'smart_contribution');
//Username
define($user, 'root');
//Password
define($pass, '');

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('Connection Error : '. mysqli_connect_error());
}

// define site path
define('DIR','http://localhost/smartcontribution/');

include('functions.php')

?>