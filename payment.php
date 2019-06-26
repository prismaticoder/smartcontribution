<?php 
require_once('partials/header.php');

if (!isset($_GET['custNo']) or $_GET['custNo'] == '') {
    $rows = null;
}
else {
    $card_no = $_GET['custNo'];
    $query = ("SELECT main_customers.customer_id,main_customers.card_no,main_customers.customer_name,main_customers.customer_phone_num,main_customers.reg_date,main_customers.loan_rate,main_customers.savings_rate,main_customers.balance,main_customers.loan_collected,zone.zone 
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

//Submit Daily Savings
if (isset($_POST['savings_submit'])) {
    if (!empty($_POST['savings_total']) and !empty($_POST['transaction_date'])) {
        $savingsArray = [];
        $savingsArray['amount'] = $_POST['savings_total'];
        $savingsArray['transaction_date'] = $_POST['transaction_date'];
        $savingsArray['customer_id'] = $_POST['id'];
        $savingsArray['dayNo'] = $_POST['savingsDayNo'];
        $savingsArray['balance'] = $_POST['balance'];
        $savingsArray['savings_rate'] = $_POST['savings_rate'];
        //Function to execute contribution
        exec_contribution($savingsArray, 'dailysavings');    
    }
}

//Submit Loan Savings
if (isset($_POST['loan_submit'])) {
    if (!empty($_POST['loan_total']) and !empty($_POST['transaction_date'])) {
        $loanArray = [];
        $loanArray['amount'] = $_POST['loan_total'];
        $loanArray['transaction_date'] = $_POST['transaction_date'];
        $loanArray['customer_id'] = $_POST['id'];
        $loanArray['dayNo'] = $_POST['loanDayNo'];
        $loanArray['balance'] = $_POST['balance'];
        $loanArray['loan_rate'] = $_POST['loan_rate'];
        //Function to execute contribution
        exec_contribution($loanArray, 'offsetloan');    
    }
}

if (isset($_POST['submitLoan'])) {
    if ($_POST['balance'] >= $_POST['loan_amount']) {
        $collectionArray = [];
        $collectionArray['transaction_date'] = $_POST['transaction_date'];
        $collectionArray['customer_id'] = $_POST['id'];
        $collectionArray['amount'] = $_POST['loan_amount'];
        $collectionArray['author'] = $_POST['guarrantor'];
        $collectionArray['balance'] = $_POST['balance'];

        exec_loan($collectionArray);
    }
    else {
        echo "<script>alert('Error! Insufficient Balance')</script>";
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
                    <input class="form-control" type="search" id="custNo" name="custNo" value="<?php printf($rows['card_no']) ?>">
                    <input type="hidden" value="<?php printf($rows['customer_id']) ?>" name="id" id="customerID">
                    
                    
                </div>
                <button type="submit" class="btn btn-primary">Search! <i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="col-md-5"></div>
    </div>
    <hr>
    <div class="row w3-padding-24">
        <div class="col-lg-4"></div>
        <div class="col-lg-4"><button id="viewTransactions" class="btn btn-danger">View All Customer Transactions</button></div>
        <div class="col-lg-4"></div>
    </div>
    <div class="row w3-padding-24">
        <div class="col-lg-3">
            <h4 class="header-text">CUSTOMER DETAILS</h4>
            <hr>
            <table class="table-bordered"  cellpadding="3">
                <tr>
                    <td class="selector">Card No</td> <td class = "selectorValue"><i class="w3-transparent"><?php printf($rows['card_no'])?></i></td>
                </tr>
                <tr>
                    <td class="selector">Name</td> <td class = "selectorValue"><i class="w3-transparent"><?php printf($rows['customer_name'])?></i></td>
                </tr>
                <tr>
                    <td class="selector">Phone Number</td> <td class = "selectorValue"><i class="w3-transparent"><?php printf($rows['customer_phone_num'])?></i></td>
                </tr>
                <tr>
                    <td class="selector">Zone</td> <td class = "selectorValue"><i class="w3-transparent"><?php printf($rows['zone'])?></i></td>
                </tr>
                <tr>
                    <td class="selector">Date Joined</td> <td class = "selectorValue"><i class="w3-transparent"><?php printf($rows['reg_date'])?></i></td>
                </tr>
                <tr>
                    <td class="selector">Savings Rate</td> <td class = "selectorValue ajaxSelector"><i class="w3-transparent">None</i></td>
                </tr>
                <tr>
                    <td class="selector">Loan Rate</td> <td class = "selectorValue ajaxSelector"><i class="w3-transparent">None</i></td>
                </tr>
                <tr>
                    <td class="selector">Month</td> <td class = "selectorValue"><?php echo date('M',strtotime(date('Y-m-d'))); ?></td>
                </tr>
                <tr>
                    <td class="selector">Daily Contributions This Month (Savings)</td> <td class="selectorValue ajaxSelector"></td>
                </tr>
                <tr>
                    <td class="selector">Daily Contributions This Month (Loan)</td> <td class="selectorValue ajaxSelector"></td>
                </tr>
                <tr>
                    <td class="selector">Loan Collected</td> <td class = "selectorValue ajaxSelector"></td>
                </tr>
                <tr>
                    <td class="selector">Current Balance</td> <td class = "selectorValue ajaxSelector"></td>
                </tr>
                <tr>
                    <td colspan="2"><button data-toggle="modal" data-target="#editModal<?php printf($rows['card_no']) ?>" class="btn btn-primary">Edit Savings Rate/Loan Rate</button></td>
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
                        <input name="transaction_date" type="text" class="datepicker form-control" placeholder='&#128197;' value="<?php echo date('Y-m-d')?>">
                        <input name="balance" type="hidden" value="<?php echo getBalance($rows['customer_id']) ?>">
                    </td>
                </tr>
            
                <tr>
                    <td>
                        <label for="srate">Savings Rate</label>
                        <input id="savings_rate" name="savings_rate" type="number" readonly class="form-control" value="">
                        <input  type="hidden" value="">
                    </td>
                    <td>
                            <label for="lrate">Loan Rate</label>
                            <input id="loan_rate" name="loan_rate" type="number" readonly class="form-control" value="">
                    </td>
                </tr>
                <tr>
                    <td>
                            <label for="savingsDayNo">No of Days</label>
                            <input min="1" max="31" id="savingsDayNo" name="savingsDayNo" type="number" class="form-control" value="1">
                    </td>
                    <td>
                            <label for="loanDayNo">No of Days</label>
                            <input min="1" max="31" step="1"  id="loanDayNo"  name="loanDayNo" type="number"  class="form-control" value="1">
                    </td>
                </tr>
                <tr>
                    <td>
                            Total :
                            <input id="savings_total" readonly name="savings_total" type="number" class="" value="1">
                            <input type="hidden" name="id" value="<?php printf($rows['customer_id'])?>">
                    </td>
                    <td>
                            Total :
                            <input id="loan_total" readonly name="loan_total" type="number" class="" value="1">
                            <input type="hidden" name="id" value="<?php printf($rows['customer_id'])?>">
                    </td>
                </tr>
                <tr>
                    <td>
                            <button type="submit" id="savings_submit" name="savings_submit" class="btn btn-primary form-control">Submit!</button>
                    </td>
                    <td>
                            <button type="submit" id="loan_submit" name="loan_submit" class="btn btn-danger form-control">Submit!</button>
                    </td>
                </tr>
            </form>
            </table>
        </div>
        <div class="col-lg-1"></div>
        <div class="col-lg-4">
            <h4 class="header-text">LOANS</h4>
            <hr>
            <table id="loanDiv" class="my-table" border = "0" class="w3-padding-16" cellpadding="10">
                <form method="post" autocomplete="off">
                    <tr>
                        <td colspan="2">
                            <label for="date">Date of Transaction</label>
                            <input name="transaction_date" type="text" class="datepicker form-control" placeholder='&#128197;' value="<?php echo date('Y-m-d')?>">
                            <input type="hidden" name="id" value="<?php printf($rows['customer_id'])?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                                <label for="guarrantor">Guarrantor</label>
                                <input required id="guarrantor" name="guarrantor" type="text" class="form-control" placeholder="Card No e.g A1,B23...  ">
                                <small id="errorText"></small>
                                <input name="balance" type="hidden" value="<?php echo getBalance($rows['customer_id']) ?>">
                                <input id="cardNo" type="hidden" value="<?php printf ($rows['card_no']) ?>">
                        </td>
                        <td>
                                <button id="validator" name="validator" class="btn btn-primary form-control">Submit!</button><br><br>
                                <button type="reset" id="reset" class="btn btn-danger form-control">Clear</button>
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                                <label for="amount">Amount (NGN)</label>
                                <input required id="loan_amount" name="loan_amount" type="number" class="form-control" placeholder="Loan Amount" value="">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" id="loan_collect" name="submitLoan" class="btn btn-primary form-control">Get Loan!</button>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </div>

    <table class="table table-bordered table-hover" id="transactionsTable" style="display:none;">

        <thead>
            <tr>
                <th>DATE OF TRANSACTION</th>
                <th>MONTH OF TRANSACTION</th>
                <th>SAVINGS RATE</th>
                <th>LOAN RATE</th>
                <th>NUMBER OF DAYS</th>
                <th>AMOUNT</th>
                <th>DESCRIPTION</th>
                <th>TYPE</th>
                <th>BALANCE</th>

            </tr>
        </thead>
        <tbody id="transactions">

        </tbody>
    </table>
</section>

<!-- Edit Customer Modal -->
<div class='modal fade' id='editModal<?php printf($rows['card_no']) ?>' tabindex='-1' role='dialog'data-backdrop="false" aria-labelledby='editModalLabel' aria-hidden='true'>
                <div class='modal-dialog ' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header bg-light'>
                        <!-- Edit Customer Modal-->
                            <h5 class='modal-title' id='editModalLabel'>CUSTOMER #<?php printf($rows['card_no']) ?></h5>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    <div class='modal-body'>
                        <div class='nrm-wrapper'>
                            <form method="post">
                            <div class='form-group required'>
                                <label for="card_no">Card No</label>
                                <input type="hidden" value="<?php printf($rows['customer_id']) ?>" name="id">
                                <input disabled type='text' name='card_no' value="<?php printf($rows['card_no']) ?>" class='form-control br-0' placeholder="Card Number">
                            </div>
                            <div class='form-group required'>
                                <label for='cust_name'>Customer Name</label>
                                <input disabled required type='text' name='cust_name' value="<?php printf($rows['customer_name']) ?>" class='form-control br-0' placeholder="Customer Name">
                            </div>
                            <div class='form-group'>
                                <label for='cust_no'>Phone Number</label>
                                <input disabled step="any" name='cust_no' value="<?php printf($rows['customer_phone_num']) ?>" class='form-control br-0' placeholder="e.g 09011111111">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group required">
                                        <label for="srate">Daily Savings Rate (NGN)</label>
                                        <input required type="number" id="newSavingsRate" value="<?php printf($rows['savings_rate']) ?>" id="srate" name='srate' class='form-control br-0'>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group required">
                                        <label for="lrate">Daily Loan Rate (NGN)</label>
                                        <input required type="number" id="newLoanRate" value="<?php printf($rows['loan_rate']) ?>" id="lrate" name='lrate' class='form-control br-0' value="0">
                                    </div>
                                </div>
                            </div>
                                
                            <div class='row'>
                                <div class='col-12'>
                                    <input type='submit' id='edit_submit' class='submit btn btn-info b-7 br-0 btn-block' value='EDIT CUSTOMER #<?php printf($rows['card_no']) ?>!'>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
</div>
                

<?php require_once('partials/footer.php'); ?>