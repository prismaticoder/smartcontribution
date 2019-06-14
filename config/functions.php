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
    $id = $array['id'];
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
            echo "<script>alert('Customer Successfully Added!')</script>";
            header('Location: '.DIR.'viewAllCustomers.php');
            exit();
    
        }
        else {
            echo "<script>alert('Customer Addition Unsuccessful')</script>";
        }
    }

    else if ($action == 'update') {
        if (exec_query("UPDATE `main_customers` 
        SET `card_no` = '$card_no', `customer_name` = '$name',`customer_phone_num` = '$phone_num',`reg_date` = '$reg_date',`zone_id` = '$zone_id',`savings_rate` = '$srate',`loan_rate` = '$lrate' 
        WHERE `main_customers`.`customer_id` = '$id'")) 
            {
            echo "<script>alert('Update Successful!')</script>";
            // echo "<meta http-equiv='refresh' content='0'>";
            header('Location: '.DIR.'viewAllCustomers.php');
            exit();
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


?>