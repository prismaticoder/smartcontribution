<?php 
require_once('partials/header.php'); 

//Filters are Zone and Month. Simple!
$month = date('M', strtotime(date('Y-m-d')));

$result = exec_query(
"SELECT DISTINCT main_customers.customer_name,main_customers.customer_id,main_customers.card_no,main_customers.loan_rate,main_customers.savings_rate
FROM `main_customers` 
LEFT JOIN `transactions` 
ON main_customers.customer_id = transactions.customer_id
");

$sumResult = exec_query("SELECT SUM(savings_balance),SUM(loan_balance) FROM `main_customers`");

while ($sumRow = mysqli_fetch_assoc($sumResult)) {
    $loanBalance = $sumRow['SUM(loan_balance)'];
    $savingsBalance = $sumRow['SUM(savings_balance)'];
}

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
    <h3 class="w3-center">BALANCE REPORT | <span class="headerText"><span class="dateFrom">____</span> - <span class="dateTo">____</span></span></h3>
    <table class="table" border="1">
        <tr>
            <td><h4 class="w3-center">ZONE : <span class='zone'>ALL</span></h4></td>
        </tr>
    </table>
    

</div>
<hr>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
        <div class="form-group">
                <label for="month">Choose A Month</label>    
                    <select name='month' id='month' class='form-control'>
                        <option selected value> -- </option>
                        <option>Jan</option>
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
                </div>
            <div id="dateFromDiv" class="form-group required">
            </div>
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
                <button id="balance_filter" class="btn btn-primary">Filter Table!</button>
        </div>
        <div class="col-md-9">
            <table class="table table-bordered table-responsive table-hover" id="dataTable2">

                <thead>
                <tr>

                <th>S/N</th>
                <th>CARD NO</th>
                <th>NAME</th>
                <th>L. RATE</th>
                <th>DAY NO</th>
                <th>S.RATE</th>
                <th>DAY NO</th>
                <th>LOAN BALANCE</th>
                <th>SAVINGS BALANCE</th>

                </tr>
                </thead>
                <tbody id="balanceBody">
                    <?php
                    
                    $count = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        $customer_id = $row['customer_id'];
                        echo "
                            <tr>
                            <td>".$count."</td>
                            <td>".$row['card_no']."</td>
                            <td>".$row['customer_name']."</td>
                            <td>".$row['loan_rate']."</td>
                            <td>".getContNumber($customer_id, $month,'loan')."</td>
                            <td>".$row['savings_rate']."</td>
                            <td>".getContNumber($customer_id, $month,'savings')."</td>
                            <td>".getMonthBalance($customer_id, $month,'savings')."</td>
                            <td>".getMonthBalance($customer_id, $month,'loan')."</td>
                            
                            </tr>    
                            ";
                        $count++;
                        
                    }
                    
                    ?>
                </tbody>
                <tfoot>
                <tr><td colspan="9">TOTAL LOAN BALANCE =<span id="totalLoan"><?php echo $loanBalance?></span></td></tr>
                <tr><td colspan="9">TOTAL SAVINGS BALANCE =<span id="totalSavings"><?php echo $savingsBalance?></span></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php require_once('partials/footer.php')?>