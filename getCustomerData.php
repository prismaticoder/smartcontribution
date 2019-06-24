<?php

require_once('config/config.php');

if (isset($_POST['custNo'])) {
    $custno = $_POST['custNo'];

    $result = exec_query("SELECT * FROM `main_customers` WHERE `card_no` = '$custno'");

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

    $response = array($response1,$response2);

    echo json_encode($response);

}


?>