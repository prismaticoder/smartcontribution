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

function check_customer($customer_no) {
    //Function to validate if a customer already exists before adding to the db
    $customer_no = strtolower($customer_no);
    $result = exec_query("SELECT * FROM `main_customers` WHERE `customer_id` = '$customer_no'");

    if (mysqli_num_rows($result) !== 0) {
        echo "<script>alert('A Customer already exists for this particular number!')</script>";
    }
    else {
        //Execute The SQL statement to add into the db
    }
}


?>