<?php

require_once('partials/header.php');

if ($role !== "SUPERADMIN") {
    header('Location: ' .DIR. 'index.php');
    exit();
}

$result1 = exec_query("SELECT users.role_id,users.username,users.password,users.id,roles.role FROM users INNER JOIN roles ON users.role_id = roles.role_id");

$result2 = exec_query("SELECT * FROM `zone` WHERE 1");

$result3 = exec_query("SELECT * FROM `roles` WHERE 1");

if (isset($_POST['addUserSubmit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role_id = $_POST['role'];

    $resultSubmit = exec_query("INSERT INTO `users` (`role_id`,`username`,`password`) VALUES ('$role_id','$username','$password') ");

    if ($resultSubmit) {
        echo "<script>alert('User Addition Successful!')</script>";
    }
}

if (isset($_POST['addZoneSubmit'])) {
    $zone_code = $_POST['zone_code'];
    $zone_name = $_POST['zone_name'];
    $zone_officer = $_POST['zone_officer'];

    $resultSubmit = exec_query("INSERT INTO `zone` (`zone_code`,`zone`,`zone_officer`) VALUES ('$zone_code','$zone_name','$zone_officer') ");

    if ($resultSubmit) {
        echo "<script>alert('Zone Addition Successful!')</script>";
    }
}

?>

<div class="container">

<table class="table table-bordered table-hover">


<thead>
<tr><td colspan="4"><h3 class="w3-center">USERS</h3></td><td><a data-toggle="modal" href="#addUserModal"><button class="btn btn-primary form-control">ADD NEW <i class="fa fa-plus"></i></button></a></td></tr>
</thead>
<tr>

<th>S/N</th>
<th>ROLE</th>
<th>USERNAME</th>
<th>PASSWORD</th>
<th>REMOVE USER</th>

</tr>

<tbody>

<?php
$i = 1;
    while ($rows = mysqli_fetch_assoc($result1)) {
        echo "
        <tr>
        <td>".$i."</td>
        <td>".$rows['role']."</td>
        <td>".$rows['username']."</td>
        <td>".$rows['password']."</td>
        <td>  
            <i title='Remove User \"".$rows['username']."\"' class='fa fa-close click-btn delete' style=\"text-align:center;\"></i> 
        </td>
        </tr>    
        <div class='modal fade' id='editUserModal". $rows['id'] ."' tabindex='-1' role='dialog' aria-labelledby='editUserModalLabel' aria-hidden='true'>
        <div class='modal-dialog ' role='document'>
            <div class='modal-content'>
                <div class='modal-header bg-light'>
                <!-- Edit User Modal-->
                    <h5 class='modal-title' id='editUserModalLabel'>EDIT USER</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='nrm-wrapper'>
                        <form method=\"post\" autocomplete=\"off\">
                            <div class=''>
                                <label for='role'>Role:</label><br>";
                            echo "</div>
                            <div class='form-group required'>
                                <label for='username'>Username</label>
                                <input required name='username' class='form-control'>
                            </div>
                            <div class='form-group required'>
                                <label for='password'>Password</label>
                                <input required type=\"password\" name='password' class='form-control'>
                            </div>
                            <div class='form-group required'>
                                <label for='conf_password'>Confirm Password</label>
                                <input required type=\"password\" name='conf_password' class='form-control'>
                            </div>
                            <div class='row'>
                                <div class='col-12'>
                                    <input type='submit' name='editUserSubmit' class='submit btn btn-info btn-block' value='Add User!'>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>";
        $i++;
    }

?>
</tbody>
</table>

<table class="table table-bordered table-hover">


<thead>
<tr><td colspan="4"><h3 class="w3-center">ZONES</h3></td><td><a data-toggle="modal" href="#addZoneModal"><button class="btn btn-danger form-control">ADD NEW <i class="fa fa-plus"></i></button></a></td></tr>
</thead>
<tr>

<th>S/N</th>
<th>ZONE CODE</th>
<th>ZONE NAME</th>
<th>ZONE OFFICER</th>
<th>EDIT/DELETE</th>

</tr>

<tbody>

<?php
$i = 1;
    while ($rows = mysqli_fetch_assoc($result2)) {
        echo "
        <tr>
        <td>".$i."</td>
        <td>".$rows['zone_code']."</td>
        <td>".$rows['zone']."</td>
        <td>".$rows['zone_officer']."</td>
        <td></td>
        </tr>    
        ";
        $i++;
    }

?>
</tbody>
</table>

    <div class='modal fade' id='addUserModal' tabindex='-1' role='dialog' aria-labelledby='addUserModalLabel' aria-hidden='true'>
        <div class='modal-dialog ' role='document'>
            <div class='modal-content'>
                <div class='modal-header bg-light'>
                <!-- Add User Modal-->
                    <h5 class='modal-title' id='addUserModalLabel'>ADD A NEW USER</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='nrm-wrapper'>
                        <form method="post" autocomplete="off">
                            <div class=''>
                                <label for='role'>Role:</label><br>
                                <?php
                                while ($rows = mysqli_fetch_assoc($result3)) {
                                    echo '
                                    
                                    <input class="w3-radio" type="radio" name="role" value="'.$rows['role_id'].'">
                                    <label>'.$rows['role'].'</label><br>
                                    
                                    ';
                                }
                                ?>
                            </div>
                            <div class='form-group required'>
                                <label for='username'>Username</label>
                                <input required name='username' id="username" class='form-control'>
                            </div>
                            <div class='form-group required'>
                                <label for='password'>Password</label>
                                <input required type="password" name='password' id="password" class='form-control'>
                            </div>
                            <div class='form-group required'>
                                <label for='conf_password'>Confirm Password</label>
                                <input required type="password" name='conf_password' id="conf_password" class='form-control'>
                            </div>
                            <div class='row'>
                                <div class='col-12'>
                                    <input type='submit' id='addUserSubmit' name='addUserSubmit' class='submit btn btn-info btn-block' value='Add User!'>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='modal fade' id='addZoneModal' tabindex='-1' role='dialog' aria-labelledby='addZoneModalLabel' aria-hidden='true'>
        <div class='modal-dialog ' role='document'>
            <div class='modal-content'>
                <div class='modal-header bg-light'>
                <!-- Add User Modal-->
                    <h5 class='modal-title' id='addZoneModalLabel'>ADD A NEW ZONE</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='nrm-wrapper'>
                        <form method="post" autocomplete="off">
                            <div class='form-group required'>
                                <label for='zone_code'>Zone Code</label>
                                <input required name='zone_code' id="zone_code" class='form-control' maxlength='4'>
                            </div>
                            <div class='form-group required'>
                                <label for='zone_name'>Zone Name</label>
                                <input required name='zone_name' id="zone_name" class='form-control'>
                            </div>
                            <div class='form-group required'>
                                <label for='zone_officer'>Zone Officer</label>
                                <input required name='zone_officer' id="zone_officer" class='form-control'>
                            </div>
                            <div class='row'>
                                <div class='col-12'>
                                    <input type='submit' id='addZoneSubmit' name='addZoneSubmit' class='submit btn btn-info btn-block' value='Add User!'>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
require_once('partials/footer.php');
?>