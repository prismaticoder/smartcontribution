<?php

require_once('../config/config.php');

$current_url = $_SERVER['REQUEST_URI'];

switch ($current_url) {
    case '/customers/createCustomer.php':
        $title = 'Add a Customer';
        break;
    case '/customers/viewAllCustomers.php':
        $title = 'View All Customers';
        break;
    case '/login.php':
        $title = 'Admin Login';
        break;
    case '/index.php':
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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/w3.css">
    <title>Smart Contribution | <?php echo $title;?> </title>
</head>
