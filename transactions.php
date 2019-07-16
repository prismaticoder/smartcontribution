<?php 
require_once('partials/header.php'); 

$result = exec_query("SELECT transactions.transaction_id,transactions.customer_id,transactions.transaction_date,transactions.month,transactions.savings_rate,transactions.loan_rate,transactions.savingsDayNo,transactions.loanDayNo,transactions.amount,transactions.description,transactions.type,transactions.balance,transactions.isReversed,main_customers.customer_name,main_customers.card_no,zone.zone FROM `transactions` INNER JOIN `main_customers` ON transactions.customer_id = main_customers.customer_id INNER JOIN `zone` ON main_customers.zone_id = zone.zone_id ORDER BY transactions.transaction_date DESC");

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

?>

<div class="container">
    <h1 class="w3-center">ISEOLUWA AL-MONYASHAU VENTURES</h1>
    <hr>
    <h3 class="w3-center">REPORT FOR TRANSACTIONS TAKEN PLACE BETWEEN <span class="dateFrom">____</span> AND <span class="dateTo">____</span></h3>
    <table class="table" border="1">
        <tr>
            <td><h4 class="w3-center">ZONE : <span class='zone'>ALL</span></h4></td>
            <td><h4 class="w3-center">TYPE : <span class='type'>ALL TRANSACTIONS</span></h4></td>
        </tr>
    </table>
    

</div>
<hr>
<div class="container-fluid">
<div class="row w3-padding-24">
                <div class="col-md-3">
                    <div class="form-group required">
                        <label for="searchForm">DateRange (From)</label>
                        <input required value="<?php echo date('Y-m-d')?>" name="dateFrom" id="dateFrom" class="form-control datepicker" placeholder='&#128197;'/>
                    </div>
                
                    <div class="form-group required">
                        <label for="searchForm">DateRange (To)</label>
                        <input required value="<?php echo date('Y-m-d')?>" name="dateTo" id="dateTo" class="form-control datepicker" placeholder='&#128197;'/>
                    </div>
                <div class="form-group">
                
                <label for="trans_type">Choose Transaction Type</label>
                <label>All Transactions</label>
                <input class="w3-radio transType" checked type="radio" name="trans_type" value="">
                <label>Daily Savings (CR)</label>
                <input class="w3-radio transType" type="radio" name="trans_type" value="Daily Savings">
                <label>Daily Loan Offset (CR)</label>
                <input class="w3-radio transType" type="radio" name="trans_type" value="Daily Loan Offset">
                <label>Loan Collection (DR)</label>
                <input class="w3-radio transType" type="radio" name="trans_type" value="Loan Collection">
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
                        <label for="zone">Select Zone</label>    
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
                        <button id="transaction_filter" class="btn btn-primary">Filter Table!</button>
                </div>

<div class="col-md-9">
<table class="table table-bordered table-responsive table-hover" id="dataTable">

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
<th>SAVINGS BALANCE</th>

</tr>
</thead>
<tbody id="transactionBody">

<?php

$count = 1;

while ($row = mysqli_fetch_assoc($result)) {

    $row['dayNumber'] = ($row['savings_rate'] == null ? $row['loanDayNo'] : $row['savingsDayNo']);
    
    $row['savings_rate'] = ($row['savings_rate'] == null ? '-' : $row['savings_rate']);
    $row['loan_rate'] = ($row['loan_rate'] == null ? '-' : $row['loan_rate']);


    if ($row['isReversed'] != 1) {
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
    }
    else {
        echo "
        <tr class='disabled w3-grey' title='This transaction has been reversed'>
        <td>".$count."</td>
        <td>".$row['card_no']."</td>
        <td>".$row['customer_name']."</td>
        <td>".$row['zone']."</td>
        <td>".$row['transaction_id']." (Reversed!)</td>
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
    }
    
    $count++;
}

?>
</tbody>
</table>
</div>
</div>
</div>


<?php require_once('partials/footer.php')?>