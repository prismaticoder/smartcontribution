<?php require_once('partials/header.php'); ?>

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
                        <input type="text" class="form-control" id="username" placeholder="Username">
                        <br>
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password">
                        <br>
                        <br>
                        <button type="submit" class="btn btn-primary submit-btn">Login</button>
                    </div>
                </form>
            </div>
            <div class="col-md-3"></div>
</body>
</html>