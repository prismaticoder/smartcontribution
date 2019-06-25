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

            
            // getCustData();
            if ($('#custNo').val() == "") {
                $('#validator').prop('disabled', true);
                $('#reset').prop('disabled', true);
                $('#guarrantor').prop('readonly', true);
                $('.datepicker').prop('readonly', true);
                $('#viewTransactions').prop('disabled', true);
                $('.selectorValue').each(function() {
                    $(this).html("<i class=\"w3-transparent\">None</i>");
                })
            }
            else {
                $.ajax({
                    url: 'getCustomerData.php',
                    method: 'POST',
                    data: {custID: $('#custNo').val()},
                    dataType: 'json',
                    success: function(response) {
                        if (response[0] !== 'enable') {
                            $('#loanDiv').prepend('<h6><i>Note: You have to be registered for at least 3 months to be eligible for a loan</i></h6>');
                            $('#validator').prop('disabled', true);
                            $('#reset').prop('disabled', true);
                            $('#guarrantor').prop('disabled', true);
                            $('#loan_collect').prop('disabled', true);
                            $('#loan_amount').prop('disabled', true);
                            $('#loanDayNo').prop('disabled', true);
                            $('#loan_submit').prop('disabled', true);
                        }
                        if (response[1] == 'noloan') {
                            $('#loanDayNo').prop('disabled', true);
                            $('#loan_submit').prop('disabled', true);
                        }
                    }
                })
                $.ajax({
                    url: 'getCustomerData.php',
                    method: 'POST',
                    data: {customerID: $('#customerID').val()},
                    dataType: 'json',
                    success: function(response) {
                        let i = 0;
                        $('.ajaxSelector').each(function() {
                            $(this).html('<i class=\'w3-transparent\'>'+response[i]+'</i>')
                            i++
                        })
                        $('#savings_rate').val(response[0]);
                        $('#loan_rate').val(response[1]);
                    }
                })
                $('#savingsDayNo').change(function() {
                    let srate = $('#savings_rate').val();
                    let dayNo = $(this).val();

                    let total = srate * dayNo;
                    $('#savings_total').val(total);
                })
                $('#loanDayNo').change(function() {
                    let lrate = $('#loan_rate').val();
                    let dayNo = $(this).val();

                    let total = lrate * dayNo;
                    $('#loan_total').val(total);
                })
                // let custNo = $('#guarrantor').val()
                $("#guarrantor").change(function() {
                    $.ajax({
                    url: 'getCustomerData.php',
                    method: 'POST',
                    data: {
                        custNo: $(this).val(),
                        cardNo: $('#cardNo').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                            // let response = JSON.parse(responses)
                            $('#errorText').html(response[0]);
                            if (response[0] == "Customer Exists! Click 'Submit'") {
                                $('#errorText').css('color','green')
                                $('#validator').prop('disabled',false)
                                $('#validator').click(function() {
                                    $("#guarrantor").val(response[1]);
                                })
                            }
                            else {
                                $('#validator').prop('disabled',true)
                                $('#errorText').css('color','red')
                            }
                        }
                    
                    })
                })
                $('#edit_submit').click(function() {
                    $.ajax({
                        url: 'getCustomerData.php',
                        method: 'POST',
                        data: {
                            savings_rate: $('#newSavingsRate').val(),
                            loan_rate: $('#newLoanRate').val(),
                            id: $('#customerID').val()
                        },
                        success: function(response) {
                            alert(response);
                            $('#loan_rate').val($('#newLoanRate').val());
                            $('#savings_rate').val($('#newSavingsRate').val());
                        }
                    })
                })
                $('#viewTransactions').click(function() {
                    $.ajax({
                        url: 'getCustomerData.php',
                        method: 'POST',
                        data: {id: $('#customerID').val()},
                        success: function(response) {
                            $('#transactionsTable').css('display','block')
                            $('#transactions').append(response);
                            // window.location.hash = '#transactionsTable';
                        }
                    })
                })
            }

            function getCustData() {
                $.ajax({
                    url: 'getCustomerData.php',
                    method: 'GET',
                    // data: {custNo},
                    dataType: 'json',
                    success: function(response) {
                        let i = 0;
                        $('.selectorValue').each(function() {
                            $(this).html(response[i]);
                            i++;
                        })
                        $('#custNo').val(custNo)
                        
                    }
                })
            }
                
            
                        });
            
                
                
            
  </script>
    <title>The PayDay App | <?php echo $title;?> </title>
</head>

<body>

<header class="container-fluid w3-container w3-padding-16 powder-blue my-header">
    <div class="w3-left">
    <h2 class="w3-text-light-grey"><a href="index.php"><img src="lib/smart-icon.png" width="50" height="50"/><i> The PayDay App</i></a></h2>
    </div>
    <div class="w3-right w3-padding-16">
        <a href="./index.php"><button class="btn btn-dark"><i class="fa fa-home"></i> Home</button></a>
        <a href="./viewAllCustomers.php"><button type="button" class="btn btn-dark"><i class="fa fa-users"></i> Customers </button></a>
        <a href="./transactions.php"><button type="" class="btn btn-dark"><i class="fa fa-book"></i> Reports</button></a>
        <a href="./payment.php"><button type="" class="btn btn-dark"><i class="fa fa-euro"></i> Perform Transaction</button></a>
        <?php if ($role == "SUPERADMIN") {echo "<a href=\"admin_section.php\"><button class=\"btn btn-dark\"><i class=\"fa fa-gear\"></i> Admins</button></a>"; } ?>
        <a href="./?logout"><button type="" class="btn btn-dark"><i class=" fa fa-user"></i> <?php echo strtoupper($user) . ' ['.ucfirst(strtolower($role)).']'?> (Logout)</i></button></a>
    </div>
</header>
<hr>

