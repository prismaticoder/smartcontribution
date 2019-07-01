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
    $result = exec_query("SELECT * FROM `transactions` WHERE `customer_id` = '$id'  ORDER BY `transaction_date` DESC");

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['dayNumber'] = ($row['savings_rate'] == null ? $row['loanDayNo'] : $row['savingsDayNo']);
            $row['savings_rate'] = ($row['savings_rate'] == null ? '-' : $row['savings_rate']);
            $row['loan_rate'] = ($row['loan_rate'] == null ? '-' : $row['loan_rate']);

            $response.="<tr>
            <td>".$row['transaction_date']."</td>
            <td>".$row['transaction_id']."</td>
            <td>".$row['month']."</td>
            <td>".$row['savings_rate']."</td>
            <td>".$row['loan_rate']."</td>
            <td>".$row['dayNumber']."</td>
            <td>".$row['amount']."</td>
            <td>".$row['description']."</td>
            <td>".$row['type']."</td>
            <td>".$row['balance']."</td>";
            if ($row['isReversed'] != 1) {
                $response.="<td><button class=\"btn btn-primary reverseBtn\" id=\"".$row['transaction_id']."\">Reverse Transaction</td>";
            }
            else {
                $response.="<td><button disabled title=\"This transaction has already been reversed!\" class=\"btn btn-primary\" id=\"".$row['transaction_id']."\">Reverse Transaction</td>"; 
            }

            $response.=" </tr>";
            
        }
    }
    else {
        $response .= "<td colspan='11'><h5><i>This Customer Has No Active Transactions</i></h5><td>";
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

else if(isset($_GET['searchVal'])) {
    $searchVal = $_GET['searchVal'];
    $response = "";

    $result = exec_query("SELECT main_customers.customer_id,main_customers.card_no,main_customers.customer_name,main_customers.customer_phone_num,main_customers.reg_date,main_customers.loan_rate,main_customers.savings_rate,zone.zone 
    FROM `main_customers` 
    INNER JOIN `zone` 
    ON main_customers.zone_id = zone.zone_id WHERE `customer_name` LIKE '%{$searchVal}%' or `card_no` LIKE '%{$searchVal}%'");

    $resultCount = mysqli_num_rows($result);

    $count = 1;
    $response.="<tr><td colspan='7'><h5><i class='w3-transparent'>Found ".$resultCount." results matching the search string \"".$searchVal."\"</i></h5></td></tr>";
    while($rows = mysqli_fetch_assoc($result)) {
        $response.="
            <tr>
            <td>". $count ."</td>
            <td>". $rows['card_no'] ."</td>
            <td>" . $rows['customer_name'] . "</td>
            <td>" . $rows['customer_phone_num'] . "</td>
            <td>" . $rows['reg_date'] . "</td>
            <td>" . $rows['zone'] . "</td>
            <td style='text-align:center'> 
            <a title='View Customer Details' href='./payment.php?custNo=". $rows['card_no'] ."'<i class='fa fa-external-link click-btn view'></i></a> 
            <a title='Edit Customer Details' data-toggle=\"modal\" href=\"#editJobModal". $rows['card_no'] ."\"><i class='fa fa-pencil click-btn edit'></i></a> 
            <i class='fa fa-close click-btn delete'></i> </td>
            </tr>";
        $count++;
    }

    exit($response);
}

else if (isset($_POST['cardForm'])) {
    $searchVal = $_POST['cardForm'];

    $response = "";

    $result = exec_query("SELECT transactions.transaction_id,transactions.customer_id,transactions.transaction_date,transactions.month,transactions.savings_rate,transactions.loan_rate,transactions.savingsDayNo,transactions.loanDayNo,transactions.amount,transactions.description,transactions.type,transactions.balance,main_customers.customer_name,main_customers.card_no,zone.zone FROM `transactions` INNER JOIN `main_customers` ON transactions.customer_id = main_customers.customer_id INNER JOIN `zone` ON main_customers.zone_id = zone.zone_id WHERE `card_no` LIKE '$searchVal' ORDER BY transactions.transaction_date DESC");

    $count = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $row['dayNumber'] = ($row['savings_rate'] == null ? $row['loanDayNo'] : $row['savingsDayNo']);
    
        $row['savings_rate'] = ($row['savings_rate'] == null ? '-' : $row['savings_rate']);
        $row['loan_rate'] = ($row['loan_rate'] == null ? '-' : $row['loan_rate']);
        $response.="<tr>
        <td>".$count."</td>
        <td>".$row['card_no']."</td>
        <td>".$row['customer_name']."</td>
        <td>".$row['zone']."</td>
        <td>".$row['transaction_id']."</td>
        <td>".$row['transaction_date']."</td>
        <td>".$row['month']."</td>
        <td>".$row['savings_rate']."</td>
        <td>".$row['loan_rate']."</td>
        <td>".$row['dayNumber']."</td>
        <td>".$row['amount']."</td>
        <td>".$row['description']."</td>
        <td>".$row['type']."</td>
        <td>".$row['balance']."</td>
        
        </tr>    ";
        $count++;
    }

    exit($response);
}

else if (isset($_POST['transactionID'])) {
    $id = $_POST['transactionID'];
    $transaction_date = date('Y-m-d');
    // $balance = $array['balance'] - $amount;

    $result1 = exec_query("SELECT * FROM `transactions` WHERE `transaction_id` = '$id'");

    while ($rows = mysqli_fetch_assoc($result1)) {
        $amount = $rows['amount'];
        $type = $rows['type'];
        $description = $rows['description'];
        $savingsDayNo = $rows['savingsDayNo'];
        $loanDayNo = $rows['loanDayNo'];
        $savings_rate = $rows['savings_rate'];
        $loan_rate = $rows['loan_rate'];
        $customer_id = $rows['customer_id'];
        $month = $rows['month'];
        $year = $rows['year'];
    }

    $newDesc = "Reversal of Transaction ID: ".$id;
    $curBalance = getBalance($customer_id);

    if ($type == 'CR') {
        $balance = $curBalance - $amount;
        if ($description == 'Daily Savings') {
            $result2 = exec_query("INSERT INTO `transactions` (`customer_id`,`transaction_date`,`month`,`year`,`amount`,`description`,`type`,`savings_rate`,`savingsDayNo`,`balance`,`isReversed`) VALUES ('$customer_id','$transaction_date','$month','$year','-$amount','$newDesc', 'DR','$savings_rate','-$savingsDayNo','$balance',1)");
            if ($result2) {
                $result3 = exec_query("UPDATE `main_customers` SET `balance` = `balance` - '$amount' WHERE `main_customers`.`customer_id` = '$customer_id' ");
                $result4 = exec_query("UPDATE `transactions` SET `isReversed` = 1 WHERE `transactions`.`transaction_id` = '$id'");
            }
        }
        else if ($description == 'Daily Loan Offset') {
            $result2 = exec_query("INSERT INTO `transactions` (`customer_id`,`transaction_date`,`month`,`year`,`amount`,`description`,`type`,`loan_rate`,`loanDayNo`,`balance`,`isReversed`) VALUES ('$customer_id','$transaction_date','$month','$year','-$amount','$newDesc', 'DR','$loan_rate','-$loanDayNo','$balance',1)");
            if ($result2) {
                $result3 = exec_query("UPDATE `main_customers` SET `balance` = `balance` - '$amount' WHERE `main_customers`.`customer_id` = '$customer_id' ");
                $result4 = exec_query("UPDATE `transactions` SET `isReversed` = 1 WHERE `transactions`.`transaction_id` = '$id'");
            }
        }
        $response = "Successful Transacton Reversal!";
    }
    else if ($type == 'DR') {
        $balance = $curBalance + $amount;
        $result4 = exec_query("INSERT INTO `transactions` (`customer_id`,`transaction_date`,`month`,`year`,`amount`,`description`,`type`,`balance`,`isReversed`) VALUES ('$customer_id','$transaction_date','$month','$year','$amount','$newDesc', 'CR','$balance',1)");
        if ($result4) {
            $result5 = exec_query("UPDATE `main_customers` SET `balance` = `balance` + '$amount',`loan_collected` = `loan_collected` - '$amount' WHERE `main_customers`.`customer_id` = '$customer_id' ");
            $result6 = exec_query("UPDATE `transactions` SET `isReversed` = 1 WHERE `transactions`.`transaction_id` = '$id'");
        }
        $response = "Successful Transacton Reversal!";
    }
    else {
        $response = "Error Processing Your Request";
    }

    

    exit($response);
}

?>