<?php 
require_once('partials/header.php'); 

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

if (!isset($_GET['report']) or $_GET['report'] == "") {
    $result = exec_query("SELECT transactions.transaction_id,transactions.customer_id,transactions.transaction_date,transactions.transaction_time,transactions.month,transactions.savings_rate,transactions.loan_rate,transactions.savingsDayNo,transactions.loanDayNo,transactions.amount,transactions.description,transactions.type,transactions.savings_balance,transactions.loan_balance,transactions.isReversed,main_customers.customer_name,main_customers.card_no,zone.zone FROM `transactions` INNER JOIN `main_customers` ON transactions.customer_id = main_customers.customer_id INNER JOIN `zone` ON main_customers.zone_id = zone.zone_id ORDER BY transactions.transaction_time DESC");
}

else {
    $report = $_GET['report'];

    if ($report == 'general') {
        $result = exec_query("SELECT transactions.transaction_id,transactions.customer_id,transactions.transaction_date,transactions.transaction_time,transactions.month,transactions.savings_rate,transactions.loan_rate,transactions.savingsDayNo,transactions.loanDayNo,transactions.amount,transactions.description,transactions.type,transactions.savings_balance,transactions.loan_balance,transactions.isReversed,main_customers.customer_name,main_customers.card_no,zone.zone FROM `transactions` INNER JOIN `main_customers` ON transactions.customer_id = main_customers.customer_id INNER JOIN `zone` ON main_customers.zone_id = zone.zone_id ORDER BY transactions.transaction_time DESC");

    }
    else if ($report == 'monthly') {
        $month = date('M',strtotime(date('Y-m-d')));
        $result = exec_query("SELECT transactions.transaction_id,transactions.customer_id,transactions.transaction_date,transactions.transaction_time,transactions.month,transactions.savings_rate,transactions.loan_rate,transactions.savingsDayNo,transactions.loanDayNo,transactions.amount,transactions.description,transactions.type,transactions.savings_balance,transactions.loan_balance,transactions.isReversed,main_customers.customer_name,main_customers.card_no,zone.zone FROM `transactions` INNER JOIN `main_customers` ON transactions.customer_id = main_customers.customer_id INNER JOIN `zone` ON main_customers.zone_id = zone.zone_id WHERE transactions.month = '$month' ORDER BY transactions.transaction_time DESC");

    }
    else if ($report == 'daily') {
        $day = date('Y-m-d');
        $result = exec_query("SELECT transactions.transaction_id,transactions.customer_id,transactions.transaction_date,transactions.transaction_time,transactions.month,transactions.savings_rate,transactions.loan_rate,transactions.savingsDayNo,transactions.loanDayNo,transactions.amount,transactions.description,transactions.type,transactions.savings_balance,transactions.loan_balance,transactions.isReversed,main_customers.customer_name,main_customers.card_no,zone.zone FROM `transactions` INNER JOIN `main_customers` ON transactions.customer_id = main_customers.customer_id INNER JOIN `zone` ON main_customers.zone_id = zone.zone_id WHERE transactions.transaction_date = '$day' ORDER BY transactions.transaction_time DESC");
    }
}

?>

<div class="container">
    <h1 class="w3-center">ISEOLUWA AL-MONYASHAU VENTURES</h1>
    <hr>
    <h3 class="w3-center headerText">TRANSACTION REPORT | <span class="headerText"><span class="dateFrom">____</span> - <span class="dateTo">____</span></span></h3>
    <table class="table" border="1">
        <tr>
            <td><h4 class="w3-center">ZONE : <span class='zone'>ALL</span></h4></td>
            <td><h4 class="w3-center">TYPE : <span class='type'>ALL TRANSACTIONS</span></h4></td>
        </tr>
    </table>
    

</div>
<hr>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <table class="table table-bordered trTable" align="center" border="1">
                <tr>
                <td id="gRow"><a href="?report=general">General Report</a></td>
                <td id="mRow"><a href="?report=monthly">Monthly Report</a></td>
                <td id="dRow"><a href="?report=daily">Daily Report</a></td>
                </tr>
            </table>
        </div>
        <div class="col-md-4"></div>
    </div>
<div class="row w3-padding-24">
                <div class="col-md-3">
                    <div id="dateFromDiv" class="form-group required">
                        <label for="searchForm">DateRange (From)</label>
                        <input required value="<?php echo date('Y-m-d')?>" name="dateFrom" id="dateFrom" class="form-control datepicker" placeholder='&#128197;'/>
                    </div>
                
                    <div id="dateToDiv" class="form-group required">
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
<th>LOAN BALANCE</th>

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
        <td>".$row['savings_balance']."</td>","
        <td>".$row['loan_balance']."</td>
        
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
        <td>".$row['savings_balance']."</td>","
        <td>".$row['loan_balance']."</td>
        
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

<?php

if (isset($_GET['report']) or $_GET['report'] != "") {
    $report = $_GET['report'];

    if ($report == 'general') {
        echo "<script>setGeneral();</script>";
    }
    else if ($report == 'monthly') {
        echo "<script>setMonthly();</script>";
    }
    else if ($report == 'daily') {
        echo "<script>setDaily();</script>";
    }
}

else {
    echo "<script>setGeneral();</script>";
}

?>


<?php require_once('partials/footer.php')?>