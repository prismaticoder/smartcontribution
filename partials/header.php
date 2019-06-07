<?php

require_once('config/config.php');

$current_url = $_SERVER['REQUEST_URI'];
$main_dir = 'smartcontribution';

switch ($current_url) {
    case '/'. $main_dir. 'customers/createCustomer.php':
        $title = 'Add a Customer';
        break;
    case '/'. $main_dir. 'customers/viewAllCustomers.php':
        $title = 'View All Customers';
        break;
    case '/'. $main_dir. '/login.php':
        $title = 'Admin Login';
        break;
    case '/'. $main_dir. '/index.php':
        $title = 'Home';
        break;
    default:
        $title = '';
        break;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/myStyles.css">
    <title>Smart Contribution | <?php echo $title;?> </title>
</head>

<body>

<header class="w3-container"><h2><a href="index.php"><img src="lib/smart-icon.png" width="50" height="50"/><i> Smart Contribution App</i></a></h2></header>
<hr>
