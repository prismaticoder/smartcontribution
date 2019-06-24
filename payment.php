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
        echo "<script>alert('This Customer is not eligible to request for a loan!')</script>";
    }

}

if (isset($_POST['edit_submit'])) {
    $savings_rate = $_POST['newSavingsRate'];
    $loan_rate = $_POST['newLoanRate'];
    $customer_id = $_POST['id'];

    $result = exec_query("UPDATE `main_customers` SET `savings_rate` = '$savings_rate',`loan_rate` = '$loan_rate' WHERE `customer_id` = '$customer_id'");

    echo "<script>alert('Details Updated Successfully, Refresh Page to see changes')</script>";
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
    <hr>
    <div class="row w3-padding-24">
        <div class="col-lg-4"></div>
        <div class="col-lg-4"><button class="btn btn-danger">REVERSE LAST TRANSACTION</button></div>
        <div class="col-lg-4"></div>
    </div>
    <div class="row w3-padding-24">
        <div class="col-lg-3">
            <h4 class="header-text">CUSTOMER DETAILS</h4>
            <hr>
            <table class="table-bordered"  cellpadding="1">
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
                    <td class="selector">Days Contributed So Far (Savings)</td> <td><?php echo getContributionNumber($rows['customer_id'],'savings')?></td>
                </tr>
                <tr>
                    <td class="selector">Days Contributed So Far (Loan)</td> <td><?php echo getContributionNumber($rows['customer_id'],'loan')?></td>
                </tr>
                <tr>
                    <td class="selector">Current Balance</td> <td><?php echo getBalance($rows['customer_id']) ?></td>
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
                        <input name="transaction_date" type="text" class="datepicker form-control" value="<?php echo date('Y-m-d')?>">
                        <input name="balance" type="hidden" value="<?php echo getBalance($rows['customer_id']) ?>">
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
            <table class="my-table" border = "0" class="w3-padding-16" cellpadding="10">
                <form method="post">
                    <tr>
                        <td colspan="2">
                            <label for="date">Date of Transaction</label>
                            <input name="transaction_date" type="text" class="datepicker form-control" value="<?php echo date('Y-m-d')?>">
                            <input type="hidden" name="id" value="<?php printf($rows['customer_id'])?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                                <label for="guarrantor">Guarrantor</label>
                                <input required id="guarrantor" name="guarrantor" type="text" class="form-control" placeholder="Search By Card Number">
                                <small id="errorText"></small>
                                <input name="balance" type="hidden" value="<?php echo getBalance($rows['customer_id']) ?>">
                        </td>
                        <td>
                                <button type="submit" id="validator" name="validator" class="btn btn-primary form-control">Validate!</button><br><br>
                                <button type="reset" id="reset" class="btn btn-danger form-control">Clear</button>
                            
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
                            <button type="submit" id="loan_collect" name="submitLoan" class="btn btn-primary form-control">Get Loan!</button>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </div>
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
                                        <input required type="number" name="newSavingsRate" value="<?php printf($rows['savings_rate']) ?>" id="srate" name='srate' class='form-control br-0'>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group required">
                                        <label for="lrate">Daily Loan Rate (NGN)</label>
                                        <input required type="number" name="newLoanRate" value="<?php printf($rows['loan_rate']) ?>" id="lrate" name='lrate' class='form-control br-0' value="0">
                                    </div>
                                </div>
                            </div>
                                
                            <div class='row'>
                                <div class='col-12'>
                                    <input type='submit' name='edit_submit' class='submit btn btn-info b-7 br-0 btn-block' value='EDIT CUSTOMER #<?php printf($rows['card_no']) ?>!'>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
</div>
                

<?php require_once('partials/footer.php'); ?>