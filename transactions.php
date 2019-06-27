<?php 
require_once('partials/header.php'); 

$result = exec_query("SELECT transactions.transaction_id,transactions.customer_id,transactions.transaction_date,transactions.month,transactions.savings_rate,transactions.loan_rate,transactions.savingsDayNo,transactions.loanDayNo,transactions.amount,transactions.description,transactions.type,transactions.balance,main_customers.customer_name,main_customers.card_no,zone.zone FROM `transactions` INNER JOIN `main_customers` ON transactions.customer_id = main_customers.customer_id INNER JOIN `zone` ON main_customers.zone_id = zone.zone_id ORDER BY transactions.transaction_date DESC");

$zone_result = exec_query("SELECT zone_id,zone FROM `zone` WHERE 1");
$zones = [];
while ($zone_rows = mysqli_fetch_assoc($zone_result)) {
    $zones[] = $zone_rows['zone'];
    if (isset($_POST['zone'])) {
        if ($zone_rows['zone'] == $_POST['zone']) {
            $_POST['zone_id'] = $zone_rows['zone_id'];
        }
    }
};

if (isset($_GET['filterBtn'])) {
    $query = "SELECT * FROM table";

$filtered_get = array_filter($_GET); // removes empty values from $_GET

if (count($filtered_get)) { // not empty
    $query .= " WHERE";

    $keynames = array_keys($filtered_get); // make array of key names from $filtered_get

    foreach($filtered_get as $key => $value)
    {
       $query .= " $keynames[$key] = '$value'";  // $filtered_get keyname = $filtered_get['keyname'] value
       if (count($filtered_get) > 1 && (count($filtered_get) > $key)) { // more than one search filter, and not the last
          $query .= " AND";
       }
    }
}
$query .= ";";
}

?>

<div class="container-fluid">
<div class="row w3-padding-24">
                <div class="col-md-3">
                    <form method="get">
                    <div class="form-group">
                        <label for="cardForm">Card No</label>
                        <input name="custNo" class="form-control" id="cardForm" placeholder="Search By Card No, Name"/>
                    </div>
                    <!-- <button id="searchBtn" class="btn btn-danger" type="submit">Go!</button> -->
                    <div class="form-group">
                        <label for="searchForm">DateRange (From)</label>
                        <input name="dateFrom" class="form-control datepicker" placeholder='&#128197;'/>
                    </div>
                
                    <div class="form-group">
                        <label for="searchForm">DateRange (To)</label>
                        <input name="dateTo" class="form-control datepicker" placeholder='&#128197;'/>
                    </div>
                
                    <!-- <div class="form-group">
                        <label for="searchForm">Month</label>
                        <select name='month' id='' class='form-control'>
                            <option selected>Jan</option>
                            <option>Feb</option>
                            <option>Mar</option>
                            <option>Apr</option>
                            <option>May</option>
                            <option>Jun</option>
                            <option>Jul</option>
                            <option>Aug</option>
                            <option>Sep</option>
                            <option>Oct</option>
                            <option>Nov</option>
                            <option>Dec</option>

                        </select>
                    </div> -->
                <!-- <div class="col-md-2">
                    <div class="form-group">
                        <label for="searchForm">Year</label>
                        <input class="form-control datepicker"/>
                    </div>
                </div> -->
                        <div class="form-group">
                        <label for="zone">Filter by Zone</label>    
                            <select name='zone' id='zone' class='form-control'>
                                <option selected value> All </option>
                                                    <?php
                                                        for ($i=0; $i < count($zones) ; $i++) {
                                                            if (null !== $_GET['zone'] and $zones[$i] == $_GET['zone']) {
                                                                echo "<option selected>".$zones[$i]."</option>";
                                                            }
                                                            else {
                                                                echo "<option>".$zones[$i]."</option>";
                                                            }
                                                        }
                                                    ?>
                                </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter Table!</button>
                    </form>
                </div>

<div class="col-md-9">
<table class="table table-bordered table-responsive table-hover">

<thead>
<tr>

<th>S/N</th>
<th>CARD NO</th>
<th>NAME</th>
<th>ZONE</th>
<th>TRANSACTION ID</th>
<th>TRANSACTION DATE</th>
<th>MONTH</th>
<th>S.RATE</th>
<th>L.RATE</th>
<th>NO. OF DAYS</th>
<th>AMOUNT</th>
<th>DESC</th>
<th>TYPE</th>
<th>BALANCE</th>

</tr>
</thead>
<tbody id="transactionBody">

<?php

$count = 1;

while ($row = mysqli_fetch_assoc($result)) {

    $row['dayNumber'] = ($row['savings_rate'] == null ? $row['loanDayNo'] : $row['savingsDayNo']);
    
    $row['savings_rate'] = ($row['savings_rate'] == null ? '-' : $row['savings_rate']);
    $row['loan_rate'] = ($row['loan_rate'] == null ? '-' : $row['loan_rate']);



    echo "
    <tr>
    <td>".$count."</td>
    <td>".$row['card_no']."</td>
    <td>".$row['customer_name']."</td>
    <td>".$row['zone']."</td>
    <td>".$row['transaction_id']."</td>
    <td>".$row['transaction_date']."</td>
    <td>".$row['month']."</td>
    <td>".$row['savings_rate']."</td>
    <td>".$row['loan_rate']."</td>
    <td>".$row['dayNumber']."</td>
    <td>".$row['amount']."</td>
    <td>".$row['description']."</td>
    <td>".$row['type']."</td>
    <td>".$row['balance']."</td>
    
    </tr>    
    ";    
    $count++;
}

?>
</tbody>
</table>
</div>
</div>


<?php require_once('partials/footer.php')?>;