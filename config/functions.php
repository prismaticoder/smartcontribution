<?php
//Function  to log a user in
function login($user, $pass) {
    $user = strip_tags(mysqli_real_escape_string($user));
    $pass = strip_tags(mysqli_real_escape_string($pass));

    //Convert password to md5 hash string
    $pass = md5($pass);

    $query = "SELECT * FROM `users` WHERE username = $user AND password = $pass";
    $result = mysqli_query($query) or die(mysqli_error());

    $role_id = mysqli_fetch_assoc($result)['role_id'];
    $role_query = "SELECT `role` FROM `roles` WHERE role_id = $role_id";
    $role = mysqli_query($role_query) or die(mysqli_error());
    
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['authorized'] = true;
        $_SESSION['role'] = $role ;

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
}

?>