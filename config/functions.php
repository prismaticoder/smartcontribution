<?php

function exec_query($query) {
    $gcon = $GLOBALS['connection'];
    $result = mysqli_query($gcon,$query) or die(mysqli_error($gcon));
    return $result;
}

//Function  to log a user in
function login($user, $pass) {
    $gcon = $GLOBALS['connection'];
    $user = strip_tags(mysqli_real_escape_string($gcon,$user));
    $pass = strip_tags(mysqli_real_escape_string($gcon,$pass));

    //Convert password to md5 hash string
    // $pass = md5($pass);

    $result = exec_query("SELECT * FROM `users` WHERE `username` = '$user' AND `password` = '$pass'");
    
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['authorized'] = true;

        while ($result_row = mysqli_fetch_assoc($result)) {
            $role_id = $result_row["role_id"];
        };
        
        $role_result = exec_query("SELECT * FROM `roles` WHERE role_id = '$role_id'");

        while($role_row = mysqli_fetch_assoc($role_result)) {
            $role = $role_row['role'];
        }
        

        $_SESSION['role'] = $role ;
        $_SESSION['user'] = $user ; 

        header('Location: '. DIR. 'index.php');
        exit();
    }
    else {
        echo ("<script>alert('Incorrect username or password entered!')</script>");
    }

    //I'm not through with this function yet...
}
//Function to check if a user is logged in
function is_logged_in() {
    if ($_SESSION['authorized'] == true) {
        return true;
    }
    else {
        return false;
    }
};
//Function to check if a user is logged in, if not, redirect to the login page
function login_required() {
    if(is_logged_in()) {
        return true;
    }
    else {
        header('Location: '.DIR.'login.php');
        exit();
    }
};

//Function to Log a user out
function logout() {
    if (isset($_GET['logout'])) {
        unset($_SESSION['authorized']);
        header('Location: '.DIR.'login.php');
        exit();
    }
};

//Function to calculate balance
function calculate_balance($rate, $cur_date, $reg_date) {
    $date_diff = $cur_date - $reg_date;

//Get the date_diff
//Balance is rate multiplied by the rate diff in addition to the left amount for the previous transaction.
}

//Function to perform an action on the customer details based on what the user decides to do
function customerAction($array,$action) {
    $card_no = $array['card_no'];
    $name = $array['name'];
    $phone_num = $array['phone_num'];
    $reg_date = $array['reg_date'];
    $zone_id = $array['zone_id'];
    $srate = $array['srate'];
    $lrate = $array['lrate'];
    $author = $array['author'];
    
    if ($action == 'add') {
        if (exec_query("INSERT INTO main_customers 
        (`card_no`,`customer_name`,`customer_phone_num`,`reg_date`,`zone_id`,`savings_rate`,`loan_rate`,`author`) VALUES 
        ('$card_no','$name','$phone_num','$reg_date',$zone_id,'$srate','$lrate','$author')")) 
            {
            echo "<script>
            alert('Customer Successfully Added!');
            window.location.href='./viewAllCustomers.php';
            </script>";
    
        }
        else {
            echo "<script>
            alert('Customer Addition Unsuccessful!');
            window.location.href='./viewAllCustomers.php';
            </script>";
        }
    }

    else if ($action == 'update') {
        $id = $array['id'];
        if (exec_query("UPDATE `main_customers` 
        SET `card_no` = '$card_no', `customer_name` = '$name',`customer_phone_num` = '$phone_num',`reg_date` = '$reg_date',`zone_id` = '$zone_id',`savings_rate` = '$srate',`loan_rate` = '$lrate' 
        WHERE `main_customers`.`customer_id` = '$id'")) 
            {
            echo "<script>
            alert('Customer Update Successful!');
            window.location.href='./viewAllCustomers.php';
            </script>";
        }
        else {
            echo "<script>alert('Update Unsuccessful')</script>";
        }
    }

}

function check_customer($array,$action) {
    //Function to validate if a customer already exists before adding to the db
    // $customer_no = strtolower($customer_no);
    $customer_no = $array['card_no'];
    $result = exec_query("SELECT * FROM `main_customers` WHERE `card_no` = '$customer_no'");

    if ($action == 'update') {
        $id = $array['id'];
        while ($rows = mysqli_fetch_assoc($result)) {
            $rowID = $rows['customer_id'];
        }
        if ($id == $rowID) {
            $similar = 1;
        }
        else {
            $similar = 0;
        }
    }

    if (mysqli_num_rows($result) > 0 and $similar !== 1) {
        echo "<script>alert('A Customer already exists for this particular number!')</script>";
        // header('Location: '.DIR.'viewAllCustomers.php');
        // exit();
    }
    
    else {
        customerAction($array,$action);
    }
}

function validate_gurrantor($number) {
    $result = exec_query("SELECT * FROM `main_customers` WHERE `card_no` = '$number'");

    if (mysqli_num_rows($result) !== 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
            $name = $rows['customer_name'];
            $num = $rows['card_no'];
        }
        if ($num !== $_GET['custNo']) {
            echo "
            <script>alert('Validation Successful!')</script>
            ";
            return $name;
        }
        else {
            echo "
            <script>alert('You cannot select the same customer as a guarrantor!')</script>
            ";
        }
    }
    else {
        echo "<script>
        alert('Customer Not Found!');
        </script>"; 
    }
}

function getContributionNumber($id,$type) {
    //function to get the number of contributions a user has made in a month
    $month = date('M',strtotime(date('Y-m-d')));
    if ($type == 'savings') {
        $result = exec_query("SELECT SUM(savingsDayNo) FROM `transactions` WHERE `month` = '$month' and `customer_id` = '$id' and `description` = 'Daily Savings'");

        $i = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['SUM(savingsDayNo)'] == null) {
                $sum = 0;
            }
            else {
                $sum = $row['SUM(savingsDayNo)'];
            }    
        }
    }
    else {
        $result = exec_query("SELECT SUM(loanDayNo) FROM `transactions` WHERE `month` = '$month' and `customer_id` = '$id' and `description` = 'Daily Loan Offset'");

        $i = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['SUM(loanDayNo)'] == null) {
                $sum = 0;
            }
            else {
                $sum = $row['SUM(loanDayNo)'];
            }
            
        }
    }
    
    
    return $sum;
}

function exec_contribution($array,$action) {
    //Function to execute a contribution transaction
    $current_date = date('Y-m-d');
    if ($action == 'dailysavings') {
        $transaction_date = $array['transaction_date'];
        $transaction_time = $transaction_date . " " . date('H:i:s');
        $amount = $array['amount'];
        $customer_id = $array['customer_id'];
        if ($transaction_date <= $current_date) {
            $month = date('M',strtotime($transaction_date));
            $year = date('Y',strtotime($transaction_date));
            $dayNo = $array['dayNo'];
            $balance = $array['balance'] + $amount;
            $curLoanBalance = getLoanBalance($customer_id);
            $savings_rate = $array['savings_rate'];
            $result1 = exec_query("INSERT INTO `transactions` (`customer_id`,`transaction_date`,`transaction_time`,`month`,`year`,`savings_rate`,`savingsDayNo`,`amount`,`description`,`type`,`savings_balance`,`loan_balance`) VALUES ('$customer_id','$transaction_date','$transaction_time','$month','$year','$savings_rate','$dayNo','$amount','Daily Savings','CR','$balance','$curLoanBalance')");
            if ($result1) {
                $result2 = exec_query("UPDATE `main_customers` SET `savings_balance` = `savings_balance` + '$amount' WHERE `main_customers`.`customer_id` = '$customer_id' ");
                echo "<script>alert('Contribution Added Successfully!');</script>";

            }
        }
        else {
            echo "<script>alert('Invalid Date Selected!')</script>";
        }
    }
    else {
        $transaction_date = $array['transaction_date'];
        $transaction_time = $transaction_date . " " . date('H:i:s');
        $amount = $array['amount'];
        $customer_id = $array['customer_id'];
        if ($transaction_date <= $current_date) {
            $month = date('M',strtotime($transaction_date));
            $year = date('Y',strtotime($transaction_date));
            $dayNo = $array['dayNo'];
            $balance = $array['balance'] + $amount;
            $loan_rate = $array['loan_rate'];
            $curSavingsBalance = getSavingsBalance($customer_id);
            $result1 = exec_query("INSERT INTO `transactions` (`customer_id`,`transaction_date`,`transaction_time`,`month`,`year`,`loan_rate`,`loanDayNo`,`amount`,`description`,`type`,`savings_balance`,`loan_balance`) VALUES ('$customer_id','$transaction_date','$transaction_time','$month','$year','$loan_rate','$dayNo','$amount','Daily Loan Offset','CR','$curSavingsBalance','$balance')");
            if ($result1) {
                $result2 = exec_query("UPDATE `main_customers` SET `loan_balance` = `loan_balance` + '$amount' WHERE `main_customers`.`customer_id` = '$customer_id' ");
                echo "<script>alert('Contribution Added Successfully!');</script>";

            }
        }
        else {
            echo "<script>alert('Invalid Date Selected!')</script>";
        }
    }

}

function exec_loan($array) {
    //Function to execute a Loan transaction
    $current_date = date('Y-m-d');
    $transaction_date = $array['transaction_date'];
    $transaction_time = $transaction_date . " " . date('H:i:s');
    $amount = $array['amount'];
    $customer_id = $array['customer_id'];
    $author = $array['author'];
    if ($transaction_date <= $current_date) {
        $month = date('M',strtotime($transaction_date));
        $year = date('Y',strtotime($transaction_date));
        $balance = $array['balance'] - $amount;
        $curSavingsBalance = getSavingsBalance($customer_id);

        $result1 = exec_query("INSERT INTO `transactions` (`customer_id`,`transaction_date`,`transaction_time`,`month`,`year`,`amount`,`description`,`type`,`savings_balance`,`loan_balance`,`author`) VALUES ('$customer_id','$transaction_date','$transaction_time','$month','$year','$amount','Loan Collection', 'DR','$curSavingsBalance','$balance', '$author')");
        if ($result1) {
            $result2 = exec_query("UPDATE `main_customers` SET `loan_balance` = `loan_balance` - '$amount' WHERE `main_customers`.`customer_id` = '$customer_id' ");
            echo "<script>alert('Successful Loan Collection!');</script>";

        }
    }
    else {
        echo "<script>alert('Invalid Date Selected!')</script>";
    }



}

function getSavingsBalance($id) {
    if ($id == "") {
        $balance = null;
    }
    else {
        $result = exec_query("SELECT `savings_balance` FROM `main_customers` WHERE `customer_id` = '$id'");

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['savings_balance'] == null) {
                $balance = 0;
            }
            else {
                $balance = $row['savings_balance'];
            }
        }
    }
    return $balance;
}

function getLoanBalance($id) {
    if ($id == "") {
        $balance = null;
    }
    else {
        $result = exec_query("SELECT `loan_balance` FROM `main_customers` WHERE `customer_id` = '$id'");

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['loan_balance'] == null) {
                $balance = 0;
            }
            else {
                $balance = $row['loan_balance'];
            }
        }
    }
    return $balance;
}

//Get the customer's balance at the end of the month
function getMonthBalance($customer_id,$month,$type) {
    $result = exec_query("SELECT `savings_balance`, `loan_balance` FROM `transactions` WHERE `month` = '$month' AND `customer_id` = '$customer_id' AND `transaction_time` = (SELECT MAX(transaction_time) FROM `transactions` WHERE `month` = '$month' AND `customer_id` = '$customer_id' )");

    if (mysqli_num_rows($result) == 0) {
        $balance = 0;
    }

    else {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($type == 'savings') {
                $balance = $row['savings_balance'];
            }
            else {
                $balance = $row['loan_balance'];
            }
        }
    }

    
    return $balance;
}

function getContNumber($customer_id,$month,$type) {
    $result = exec_query("SELECT SUM(savingsDayNo), SUM(loanDayNo) FROM `transactions` WHERE `customer_id` = '$customer_id' AND `month` = '$month'");

    if (mysqli_num_rows($result) == 0) {
        $answer = 0;
    }
    else {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($type == 'savings') {
                $answer = ($row['SUM(savingsDayNo)'] == null ? 0 : $row['SUM(savingsDayNo)']);
            }
            else {
                $answer = ($row['SUM(loanDayNo)'] == null ? 0 : $row['SUM(loanDayNo)']);
            }
        } 
    }

    return $answer;
}


?>