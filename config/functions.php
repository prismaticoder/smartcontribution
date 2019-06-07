<?php
//Function  to log a user in
function login($user, $pass) {
    $user = strip_tags(mysqli_real_escape_string($user));
    $pass = strip_tags(mysqli_real_escape_string($pass));

    //Convert password to md5 hash string
    $pass = md5($pass);
}
//Function to check if a user is logged in
function is_logged_in() {
    if ($_SESSION['authorized'] == true) {
        return true;
    }
    else {
        return false;
    }
}
//Function to check if a user is logged in, if not, redirect to the login page
function login_required() {
    if(is_logged_in()) {
        return true;
    }
    else if (!is_logged_in && $_SERVER['REQUEST_URI'] !== '/smartcontribution/login.php') {
        header('Location: '.DIR.'login.php');
        exit();
    }
    else {
        return true;
    }
}
//Function to Log a user out
function logout() {
    if (isset($_GET['logout'])) {
        unset($_SESSION['authorized']);
        header('Location: '.DIR.'login.php');
        exit();
    }
}

?>