<?php

require_once('config/config.php');

$current_url = $_SERVER['REQUEST_URI'];
$main_dir = 'smartcontribution';

switch ($current_url) {
    case '/'. $main_dir. '/createCustomer.php':
        $title = 'Add a Customer';
        break;
    case '/'. $main_dir. '/viewAllCustomers.php':
        $title = 'Customers';
        break;
    case '/'. $main_dir. '/payment.php':
        $title = 'Make a Payment';
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
<html lang="en" ng-app>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/myStyles.css">
    <link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" href="css/jquery-ui.min.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- <script type="text/javascript" src="js/angular.min.js"> -->
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $( function() {
            $( ".datepicker" ).datepicker({
                dateFormat: "yy-mm-dd"
            });
            if ($('#custNo').val() == "") {
                $('#validator').prop('disabled', true);
                $('#reset').prop('disabled', true);
                $('#guarrantor').prop('disabled', true);
                $('.datepicker').prop('disabled', true);
            }
            else {
                if ($('#validator').val() !== null) {
                let validatorBtn = $('#validator');
                validatorBtn.prop('disabled', true);
            }
                else {
                    let validatorBtn = $('#validator');
                    validatorBtn.prop('disabled', false);
                }
                $('#reset').click(function() {
                    $('#validator').prop('disabled', false);
                    $('#guarrantor').removeAttr('value');
                })
                $('#guarrantor').change(function() {
                    $('#validator').prop('disabled', false);
                })
            }
            
                        });
  </script>
  <script>
    //Function to automatically derive the total savings or loan without refreshing the page
  function getTotal() {
      let srate = document.getElementById('savings_rate').value;
      let dayNo = document.getElementById('dayNo').value;

      let total = srate * dayNo;

      document.getElementById('savings_total').value = total;
  }
  </script>
    <title>Smart Contribution | <?php echo $title;?> </title>
</head>

<body>

<header class="container-fluid w3-container w3-padding-16 powder-blue my-header">
    <div class="w3-left">
    <h2><a href="index.php"><img src="lib/smart-icon.png" width="50" height="50"/><i> Smart Contribution App</i></a></h2>
    </div>
    <div class="w3-right w3-padding-16">
        <a href="./?logout"><button class="btn btn-dark"><i class="fa fa-home"></i> Home</button></a>
        <button type="button" class="btn btn-dark" data-toggle="dropdown"><i class="fa fa-users"></i> Customers <i class="fa fa-angle-down"></i></button>
        <ul class="dropdown-menu" id="dropdown1">
            <li><a href="#"><i class="fa fa-angle-double-right"></i> View All Customers</a></li>
            <li><a href="#"><i class="fa fa-angle-double-right"></i> Add New Customer</a></li>
            <li><a href="#">JavaScript</a></li>
        </ul>
        <button type="" class="btn btn-dark"><i class="fa fa-book"></i> Reports <i class="fa fa-angle-down"></i></button>
        <a href="./payment.php"><button type="" class="btn btn-dark"><i class="fa fa-euro"></i> Perform Transaction</button></a>
        <?php if ($role == "SUPERADMIN") {echo "<button class=\"btn btn-dark\"><i class=\"fa fa-gear\"></i> Admins</button>"; } ?>
        <a href=""><button type="" class="btn btn-dark"><i class=" fa fa-user"></i> <?php echo strtoupper($user) . ' ['.ucfirst(strtolower($role)).']'?> <i class="fa fa-angle-down"></i></button></a>
        <ul class="dropdown-menu">
            <li><a href="./?logout"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
    </div>
</header>
<hr>

