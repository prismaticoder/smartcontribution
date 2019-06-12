<?php

require_once('config/config.php');

$current_url = $_SERVER['REQUEST_URI'];
$main_dir = 'smartcontribution';

switch ($current_url) {
    case '/'. $main_dir. '/createCustomer.php':
        $title = 'Add a Customer';
        break;
    case '/'. $main_dir. '/viewAllCustomers.php':
        $title = 'View All Customers';
        break;
    case '/'. $main_dir. '/contribution/addContribution.php':
        $title = 'Make a Contribution';
        break;
    case '/'. $main_dir. '/contribution/editContribution.php':
        $title = 'Edit Contribution';
        break;
    case '/'. $main_dir. '/reports/report.php':
        $title = 'Reports';
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
};

login_required();


if (isset($_GET['logout'])) {
    logout();
    }

//Set the uniqueness of the session;
$user = $_SESSION['user'];
$role = $_SESSION['role'];

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
    <link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.css" />
    <title>Smart Contribution | <?php echo $title;?> </title>
</head>

<body>

<header class="w3-container w3-padding-16 powder-blue">
    <div class="w3-left">
    <h2><a href="index.php"><img src="lib/smart-icon.png" width="50" height="50"/><i> Smart Contribution App</i></a></h2>
    </div>
    <div class="w3-right w3-padding-16">
        <a href="./?logout"><button class="btn btn-dark"><i style="color:#f13c20" class="fa fa-home"></i> Home</button></a>
        <button type="button" class="btn btn-dark" data-toggle="dropdown"><i style="color:#f13c20" class="fa fa-users"></i> Customers <i class="fa fa-angle-down"></i></button>
        <ul class="dropdown-menu">
            <li><a href="#"><i class="fa fa-angle-double-right"></i> View All Customers</a></li>
            <li><a href="#"><i class="fa fa-angle-double-right"></i> Add New Customer</a></li>
            <li><a href="#">JavaScript</a></li>
        </ul>
        <button type="" class="btn btn-dark"><i style="color:#f13c20" class="fa fa-book"></i> Reports <i class="fa fa-angle-down"></i></button>
        <button type="" class="btn btn-dark"><i style="color:#f13c20" class="fa fa-euro"></i> Contributions <i class="fa fa-angle-down"></i></button>
        <ul class="dropdown-menu">
            <li><a href="#"><i class="fa fa-angle-double-right"></i> Make A Contribution</a></li>
            <li><a href="#"><i class="fa fa-angle-double-right"></i> Loan Withdrawal</a></li>
            <li><a href="#">JavaScript</a></li>
        </ul>
        <a href=""><button type="" class="btn btn-dark"><i style="color:#f13c20" class=" fa fa-user"></i> <?php echo strtoupper($user) . ' ['.ucfirst(strtolower($role)).']'?></button></a>
        <ul class="dropdown-menu">
            <li><a href="./?logout"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
    </div>
</header>
<hr>

