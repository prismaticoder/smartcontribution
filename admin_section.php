<?php

require_once('partials/header.php');

$result = exec_query("SELECT users.role_id,users.username,users.password,role.role FROM users INNER JOIN roles ON users.role_id = roles.role_id")

?>



<?php
require_once('partials/footer.php');
?>