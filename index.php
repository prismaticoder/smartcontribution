<?php 
require_once('partials/header.php'); 

$customer_result = exec_query("SELECT * FROM `main_customers` WHERE 1");
$customer_no = mysqli_num_rows($customer_result);

$admin_result = exec_query("SELECT * FROM `users` WHERE 1");
$admin_no = mysqli_num_rows($admin_result);

//Indicate that login is required to view this particular page

?>

<!-- I need to implement a sidebar on this page to contain all the links. Let's leave it for now -->
<!-- I also need to make the dropdown visible in the page -->

<section class="container w3-padding-32">
    
    <h1><span class="fa fa-bar-chart-o"></span></h1>
    <div class="row w3-padding-24">
        <div class="col-md-3">
            <h3 class="main-header">Customers</h3>
            <hr>
            <span class="big-number"><?php echo $customer_no?></span>
        </div>
        <div class="col-md-3">
                <h3 class="main-header">Administrators</h3>
                <hr>
                <span class="big-number"><?php echo $admin_no?></span>
        </div>
        <div class="col-md-3">
                <h3 class="main-header">Loans Remitted</h3>
                <hr>
                <span class="big-number">112</span>
        </div>
        <div class="col-md-3">
                <h3 class="main-header">Loans Owed</h3>
                <hr>
                <span class="big-number">302</span>
        </div>
    </div>
</section>

<!-- <section class="container w3-padding-32">
    <h1 class="main-header">PAGES</h1>
    <div class="row">
        <div class="col-md-3">
            <h2 class="main-header">Customers</h2>
            <hr>
            <span class="fa fa-angle-double-right"></span> <a href="customers/viewAllCustomers.php">View All Customers</a><br>
            <span class="fa fa-angle-double-right"></span> <a href="customers/createCustomer.php">Add a Customer</a><br>
            <span class="fa fa-angle-double-right"></span> <a href="customers/editCustomers.php">Edit Customer Details</a><br> -->


</body>
</html>

