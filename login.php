<?php require_once('partials/header.php'); 

if (isset($_POST['submit'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
    }
}


?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form method="POST" action="index.php">
                    <div class="form-group login-div w3-border-blue-grey">
                        <h3><strong>SMART CONTRIBUTION ADMIN LOGIN</strong></h3>
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
</body>
</html>