<?php

require_once('config/config.php');

if (isset($_POST['custNo']) and isset($_POST['cardNo'])) {
    $custno = $_POST['custNo'];
    $current_custNo = $_POST['cardNo'];

    $result = exec_query("SELECT * FROM `main_customers` WHERE `card_no` = '$custno'");

    if (strtolower($custno) !== strtolower($current_custNo)) {
        if(mysqli_num_rows($result) == 0) {
            $response1 = "This Customer does not exist!";
            $response2 = null;
        }
        else {
            $response1 = "Customer Exists!";
            while ($row = mysqli_fetch_assoc($result)) {
                $response2 = $row['customer_name'];
            }
            
        }
    }
    else {
        $response1 = "Invalid Guarrantor Selection!";
        $response2 = null;
    }
    

    $response = array($response1,$response2);

    echo json_encode($response);

}

else if (isset($_POST['savings_rate']) and isset($_POST['loan_rate'])) {
   $savings_rate =  $_POST['savings_rate'];
   $loan_rate = $_POST['loan_rate'];
   $customer_id = $_POST['id'];

   $result = exec_query("UPDATE `main_customers` SET `savings_rate` = '$savings_rate',`loan_rate` = '$loan_rate' WHERE `customer_id` = '$customer_id'");

   $response = "Details Saved Successfully";

   exit($response);
}

else if(isset($_POST['custID'])) {
    $custID = $_POST['custID'];
    $current_month = date('m',strtotime(date('Y-m-d')));

    $result = exec_query("SELECT * FROM `main_customers` WHERE `card_no` = '$custID'");

    while ($row = mysqli_fetch_assoc($result)) {
        $reg_date = $row['reg_date'];
        $loan_collected = $row['loan_collected']; 
    }
    $reg_month = date('m',strtotime($reg_date));

    if ($current_month - $reg_month >= 3) {
        $response1 = 'enable';
    }
    else {
        $response1 = 'disable';
    }

    if ($loan_collected !== 0) {
        $response2 = 'yesloan';
    }
    else {
        $response2 = 'noloan';
    }

    $response = array($response1,$response2);

    echo json_encode($response);



}


?>