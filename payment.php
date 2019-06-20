<?php 
require_once('partials/header.php');

if (!isset($_GET['custNo']) or $_GET['custNo'] == '') {
    $rows = null;
}
else {
    $card_no = $_GET['custNo'];
    $query = ("SELECT main_customers.customer_id,main_customers.card_no,main_customers.customer_name,main_customers.customer_phone_num,main_customers.reg_date,main_customers.loan_rate,main_customers.savings_rate,main_customers.balance,zone.zone 
    FROM `main_customers` 
    INNER JOIN `zone` 
    ON main_customers.zone_id = zone.zone_id
    WHERE main_customers.card_no = '$card_no' ");


    if (mysqli_num_rows(exec_query($query)) !== 0) {
        $result = exec_query($query);
        $rows = mysqli_fetch_assoc($result);
    }
    else {
        //Alert Prompt for Displaying Error 
        echo "<script>
        alert('The Customer does not exist!');
        window.location.href='./payment.php';
        </script>";
        // header('Location: '.DIR. 'payment.php');
    }
}

if (isset($_POST['validator'])) {
    if (isset($_POST['guarrantor'])) {
        $name = validate_gurrantor($_POST['guarrantor']);
    }
}
else {
    $name = null;
}


if (isset($_POST['savings_submit'])) {
    if (!empty($_POST['savings_total']) and !empty($_POST['transaction_date'])) {
        $savings_total = $_POST['savings_total'];
        $current_date = date('Y-m-d');
        $transaction_date = $_POST['transaction_date'];
        if ($transaction_date <= $current_date) {
            $month = date('M',strtotime($transaction_date));
            $year = date('Y',strtotime($transaction_date));
            $customer_id = $_POST['id'];
            $savings_rate = $_POST['savings_rate'];
            $dayNo = $_POST['savingsDayNo'];
            $result1 = exec_query("INSERT INTO `contributions` (`customer_id`,`transaction_date`,`month`,`year`,`savings_rate`,`day_number`,`amount`) VALUES ('$customer_id','$transaction_date','$month','$year','$savings_rate','$dayNo','$savings_total')");
            if ($result1) {
                $result2 = exec_query("UPDATE `main_customers` SET `balance` = `balance` + '$savings_total' WHERE `main_customers`.`customer_id` = '$customer_id' ");
                echo "<script>alert('Contribution Added Successfully!')</script>";

            }
        }
        else {
            echo "<script>alert('Invalid Date Selected!')</script>";
        }
    }
}
?>


<!-- <h1>BEGIN WORK!!</h1> -->
<section class="container w3-padding-16">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-3">
            <form method="get">
                <div class="form-group">
                    <label for="custNo">Enter the Customer Number:</label>
                    <input class="form-control" type="search" id="custNo" name="custNo" value="<?php printf($rows['card_no'])?>">
                    
                    
                </div>
                <button type="submit" class="btn btn-primary">Search! <i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="col-md-5"></div>
    </div>
    <div class="row w3-padding-32">
        <div class="col-lg-3">
            <h4 class="header-text">CUSTOMER DETAILS</h4>
            <hr>
            <table class="my-table" border = "1" class="w3-padding-16" cellpadding="5">
                <tr>
                    <td class="selector">Card No</td> <td><?php printf($rows['card_no']) ?></td>
                </tr>
                <tr>
                    <td class="selector">Name</td> <td><?php printf($rows['customer_name']) ?></td>
                </tr>
                <tr>
                    <td class="selector">Phone Number</td> <td><?php printf($rows['customer_phone_num']) ?></td>
                </tr>
                <tr>
                    <td class="selector">Zone</td> <td><?php printf($rows['zone']) ?></td>
                </tr>
                <tr>
                    <td class="selector">Date Joined</td> <td><?php printf($rows['reg_date']) ?></td>
                </tr>
                <tr>
                    <td class="selector">Savings Rate</td> <td><?php printf($rows['savings_rate']) ?></td>
                </tr>
                <tr>
                    <td class="selector">Loan Rate</td> <td><?php printf($rows['loan_rate']) ?></td>
                </tr>
                <tr>
                    <td class="selector">Month</td> <td><?php echo date('M',strtotime(date('Y-m-d'))); ?></td>
                </tr>
                <tr>
                    <td class="selector">Days Contributed So Far (This Month)</td> <td><?php echo getContributionNumber($rows['customer_id'])?></td>
                </tr>
                <tr>
                    <td class="selector">Current Balance</td> <td><?php printf($rows['balance']) ?></td>
                </tr>
            </table>
        </div>
        <div class="col-lg-4">
            <h4 class="header-text">CONTRIBUTIONS</h4>
            <hr>
            
            <table class="my-table" border = "0" class="w3-padding-16" cellpadding="5">
            <form method="post">
                <tr>
                    <th>ADD SAVINGS</th>
                    <th>OFFSET LOAN</th>
                </tr>
                <tr>
                    
                    <td colspan="2">
                        <label for="date">Date of Transaction</label>
                        <input name="transaction_date" type="text" class="datepicker form-control" value="<?php echo date('Y-m-d')?>">
                    </td>
                </tr>
            
                <tr>
                    <td>
                        <label for="srate">Savings Rate</label>
                        <input id="savings_rate" type="number" readonly class="form-control" value="<?php printf($rows['savings_rate']) ?>">
                        <input name="savings_rate" type="hidden" value="<?php printf($rows['savings_rate'])?>">
                    </td>
                    <td>
                            <label for="lrate">Loan Rate</label>
                            <input id="loan_rate" name="loan_rate" type="number" readonly class="form-control" value="<?php printf($rows['loan_rate']) ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                            <label for="saveDay">No of Days</label>
                            <input min="0" max="31" id="dayNo" name="savingsDayNo" onchange="getTotal()" type="number" class="form-control" value="1">
                    </td>
                    <td>
                            <label for="loanDay">No of Days</label>
                            <input min="0" max="31" type="number"  class="form-control" value="1">
                    </td>
                </tr>
                <tr>
                    <td>
                            Total :
                            <input id="savings_total" name="savings_total" type="number" class="" value="1">
                            <input type="hidden" name="id" value="<?php printf($rows['customer_id'])?>">
                    </td>
                    <td>
                            Total :
                            <input id="loan_total" type="number" class="" value="1">
                    </td>
                </tr>
                <tr>
                    <td>
                            <button type="submit" name="savings_submit" class="btn btn-primary form-control">Submit!</button>
                    </td>
                    <td>
                            <button type="submit" name="loan_submit" class="btn btn-danger form-control">Submit!</button>
                    </td>
                </tr>
            </form>
            </table>
        </div>
        <div class="col-lg-1"></div>
        <div class="col-lg-4">
            <h4 class="header-text">LOANS</h4>
            <hr>
            <table class="my-table" border = "0" class="w3-padding-16" cellpadding="10">
                <form method="post">
                    <tr>
                        <td colspan="2">
                            <label for="date">Date of Transaction</label>
                            <input name="transaction_date" type="text" class="datepicker form-control" value="<?php echo date('Y-m-d')?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <form method="post"  class="required">
                                <label for="guarrantor">Guarrantor</label>
                                <input required id="guarrantor" name="guarrantor" type="text" class="form-control" placeholder="Search By Card Number" value="<?php echo $name;?>">
                        </td>
                        <td>
                                <button type="submit" id="validator" name="validator" class="btn btn-primary form-control">Validate!</button><br><br>
                                <button type="reset" id="reset" class="btn btn-danger form-control">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                                <label for="amount">Amount (NGN)</label>
                                <input required id="amount" name="loan_amount" type="number" class="form-control" placeholder="Loan Amount" value="">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" id="loan_submit" name="loan_submit" class="btn btn-primary form-control">Get Loan!</button>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </div>
</section>

<?php require_once('partials/footer.php'); ?>