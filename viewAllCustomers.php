<?php 
require_once('partials/header.php'); 

//Indicate that login is required to view this particular page

$customer_result = exec_query(
    "SELECT main_customers.customer_id,main_customers.customer_name,main_customers.customer_phone_num,main_customers.reg_date,zone.zone 
    FROM `main_customers` 
    INNER JOIN `zone` 
    ON main_customers.zone_id = zone.zone_id"
    );

$count = 1;

?>

<section class="container">
    <div class="container">
        <h2 class="w3-center"> CUSTOMERS REGISTERED TO ISEOLUWA VENTURES</h2>
        <table class="table table-striped table-bordered">
            <tr>
                <th>S/N</th>
                <th>CARD NUMBER</th>
                <th>NAME</th>
                <th>PHONE NUMBER</th>
                <th>REGISTRATION DATE</th>
                <th>ZONE</th>
                <th>VIEW/EDIT/DELETE</th>
            </tr>
            <?php
        while ($customer_rows = mysqli_fetch_assoc($customer_result)) {
            echo 
            "<tr>
            <td>". $count ."</td>
            <td>". $customer_rows['customer_id'] ."</td>
            <td>" . $customer_rows['customer_name'] . "</td>
            <td>" . $customer_rows['customer_phone_num'] . "</td>
            <td>" . $customer_rows['reg_date'] . "</td>
            <td>" . $customer_rows['zone'] . "</td>
            <td style='text-align:center'> 
            <a href='/customer.php?custNo=". $customer_rows['customer_id'] ."'<i class='fa fa-external-link fa-2x view w3-hover-text-green'></i></a> 
            <a href='/editCustomer.php?custNo=". $customer_rows['customer_id'] ."'<i class='fa fa-pencil fa-2x edit w3-hover-text-blue-grey'></i></a> 
            <i class='fa fa-close fa-2x delete w3-hover-text-red'></i> </td>
            </tr>";
            $count++;
        }
            ?>
        </table>