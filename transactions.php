<?php 
require_once('partials/header.php'); 

$result = exec_query("SELECT * FROM `transactions`");

//Indicate that login is required to view this particular page

?>

<div class="container">

<table class="table table-bordered table-hover">

<thead>
<tr>

<th>CUSTOMER NO</th>
<th>DATE OF TRANSACTION</th>
<th>MONTH OF TRANSACTION</th>
<th>SAVINGS RATE</th>
<th>LOAN RATE</th>
<th>NUMBER OF DAYS</th>
<th>AMOUNT</th>
<th>TYPE</th>
<th>BALANCE</th>

</tr>
</thead>
<tbody>

<?php

while ($row = mysqli_fetch_assoc($result)) {

    $row['dayNumber'] = ($row['savings_rate'] == null ? $row['loanDayNo'] : $row['savingsDayNo']);
    
    $row['savings_rate'] = ($row['savings_rate'] == null ? '-' : $row['savings_rate']);
    $row['loan_rate'] = ($row['loan_rate'] == null ? '-' : $row['loan_rate']);



    echo "
    <tr>
    <td>".$row['customer_id']."</td>
    <td>".$row['transaction_date']."</td>
    <td>".$row['month']."</td>
    <td>".$row['savings_rate']."</td>
    <td>".$row['loan_rate']."</td>
    <td>".$row['dayNumber']."</td>
    <td>".$row['amount']."</td>
    <td>".$row['type']."</td>
    <td>".$row['balance']."</td>
    
    </tr>    
    ";    
}

?>
</tbody>
</table>
</div>


<?php require_once('partials/footer.php')?>;