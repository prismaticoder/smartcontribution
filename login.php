<?php require_once('config/config.php'); 

if (isset($_POST['submit'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        login($user,$pass);
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/myStyles.css">
    <title>The PayDay App | Admin Login </title>
</head>

<body>

<header class="w3-container powder-blue w3-padding-16"><h2><a href="index.php"><img src="lib/smart-icon.png" width="50" height="50"/><i> Smart Contribution App</i></a></h2></header>
<hr>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form method="POST">
                    <div class="form-group well login-div w3-border-blue-grey">
                        <h3><strong>THE PAYDAY APP - ADMIN LOGIN</strong></h3>
                        <br>
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                        <br>
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <br>
                        <br>
                        <button type="submit" class="btn btn-primary submit-btn" name="submit">Login</button>
                    </div>
                </form>
            </div>
            <div class="col-md-3"></div>

<?php require_once('partials/footer.php'); ?>