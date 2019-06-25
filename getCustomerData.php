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
            $response1 = "Customer Exists! Click 'Submit'";
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

else if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $response = "";
    $result = exec_query("SELECT * FROM `transactions` WHERE `customer_id` = '$id'");

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['dayNumber'] = ($row['savings_rate'] == null ? $row['loanDayNo'] : $row['savingsDayNo']);
            $row['savings_rate'] = ($row['savings_rate'] == null ? '-' : $row['savings_rate']);
            $row['loan_rate'] = ($row['loan_rate'] == null ? '-' : $row['loan_rate']);

            $response.="<tr>
            <td>".$row['transaction_date']."</td>
            <td>".$row['month']."</td>
            <td>".$row['savings_rate']."</td>
            <td>".$row['loan_rate']."</td>
            <td>".$row['dayNumber']."</td>
            <td>".$row['amount']."</td>
            <td>".$row['description']."</td>
            <td>".$row['type']."</td>
            <td>".$row['balance']."</td>
            
            </tr> ";
        }
    }
    else {
        $response .= "<td colspan='9'><h5><i>This Customer Has No Active Transactions</i></h5><td>";
    }

    exit($response);
}

else if (isset($_POST['customerID'])) {
    $custNo = $_POST['customerID'];
    $month = date('M',strtotime(date('Y-m-d')));

    $result = exec_query("SELECT main_customers.loan_rate,main_customers.savings_rate,main_customers.balance,main_customers.loan_collected,SUM(transactions.savingsDayNo),SUM(transactions.loanDayNo) 
    FROM `main_customers` 
    INNER JOIN `transactions` 
    ON main_customers.customer_id = transactions.customer_id
    WHERE main_customers.customer_id = '$custNo' and transactions.month = '$month'");
    
    while ($rows = mysqli_fetch_assoc($result)) {
        $response1 = $rows['savings_rate'];
        $response2 = $rows['loan_rate'];
        $response3 = $rows['SUM(transactions.savingsDayNo)'];
        $response4 = $rows['SUM(transactions.loanDayNo)'];
        $response5 = $rows['loan_collected'];
        $response6 = $rows['balance'];
    }

    $response= array($response1,$response2,$response3,$response4,$response5,$response6);

    echo json_encode($response); 
}

?>