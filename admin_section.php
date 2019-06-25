<?php

require_once('partials/header.php');

$result1 = exec_query("SELECT users.role_id,users.username,users.password,roles.role FROM users INNER JOIN roles ON users.role_id = roles.role_id");

$result2 = exec_query("SELECT * FROM `zone` WHERE 1");

?>

<div class="container">

<table class="table table-bordered table-hover">


<thead>
<tr><td colspan="5"><h3 class="w3-center">USERS</h3></td></tr>
</thead>
<tr>

<th>S/N</th>
<th>ROLE</th>
<th>USERNAME</th>
<th>PASSWORD</th>
<th>EDIT/DELETE</th>

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
        <td></td>
        </tr>    
        ";
        $i++;
    }

?>
</tbody>
</table>

<table class="table table-bordered table-hover">


<thead>
<tr><td colspan="5"><h3 class="w3-center">ZONES</h3></td></tr>
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
</div>



<?php
require_once('partials/footer.php');
?>