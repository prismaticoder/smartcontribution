<?php 
require_once('partials/header.php'); 

//Indicate that login is required to view this particular page

$customer_result = exec_query("SELECT `main_customers.customer_id`,`main_customers.customer_name`,`main_customers.zone_id`,`main_customers.reg_date` FROM `main_customers` INNER JOIN `zone` ON `main_customers.zone_id` = `zone.zone_id` ");


?>

<section class="container">
    <div class="container">
        <h2 class="w3-center"> CUSTOMERS REGISTERED TO ISEOLUWA VENTURES</h2>
        <table class="table table-striped">
            <tr>
                <th>CUSTOMER NUMBER</th>
                <th>CUSTOMER NAME</th>
                <th>REGISTRATION DATE</th>
                <th>ZONE</th>
            </tr>
            <?php
        while ($customer_rows = mysqli_fetch_assoc($customer_result)) {
            echo "<tr><td>". $customer_rows['customer_id'] ."</td><td>" . $customer_rows['customer_name'] . "</td><td>" . $customer_rows['reg_date'] . "</td><td>" . $customer_rows['zone_id'] . "</td></tr>";
        }
            ?>
        </table>