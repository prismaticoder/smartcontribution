<?php 
require_once('partials/header.php'); 

//Indicate that login is required to view this particular page

if (!isset($_GET['zone']) or $_GET['zone'] == '') {
    $customer_result = exec_query(
        "SELECT main_customers.customer_id,main_customers.card_no,main_customers.customer_name,main_customers.customer_phone_num,main_customers.reg_date,main_customers.loan_rate,main_customers.savings_rate,zone.zone 
        FROM `main_customers` 
        INNER JOIN `zone` 
        ON main_customers.zone_id = zone.zone_id"
        );
    $selectZone = 'ALL ZONES';
    $customer_count = mysqli_num_rows($customer_result);
}
else {
    $zone = $_GET['zone'];
    $selectZone = strtoupper($_GET['zone']);
    $customer_result = exec_query("SELECT main_customers.customer_id,main_customers.card_no,main_customers.customer_name,main_customers.customer_phone_num,main_customers.reg_date,main_customers.loan_rate,main_customers.savings_rate,zone.zone 
    FROM `main_customers` 
    INNER JOIN `zone` 
    ON main_customers.zone_id = zone.zone_id
    WHERE zone.zone = '$zone' ");
    $customer_count = mysqli_num_rows($customer_result);
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

$count = 1;

// Only view customer should not be a modal, the rest (add new customer and edit existing customer should be in form of a modal)
//Ask user if he wants to discard changes if he opts to close the edit modal. Don't just let him close it like that

$add_array = [];
$edit_array = [];

if (isset($_POST['add_submit'])) {
    $add_array['card_no'] = $_POST['card_no'];
    $add_array['name'] = $_POST['cust_name'];
    $add_array['phone_num'] = $_POST['cust_no'];
    $add_array['reg_date'] = $_POST['reg_date'];
    $add_array['zone_id'] = $_POST['zone_id'];
    $add_array['srate'] = $_POST['srate'];
    $add_array['lrate'] = $_POST['lrate'];
    $add_array['author'] = $user . "(" .$role.")";

    check_customer($add_array,'add');
}

if (isset($_POST['editSubmit'])) {
    $edit_array['id'] = $_POST['customerID'];
    $edit_array['card_no'] = $_POST['card_no'];
    $edit_array['name'] = $_POST['cust_name'];
    $edit_array['phone_num'] = $_POST['cust_no'];
    $edit_array['reg_date'] = $_POST['reg_date'];
    $edit_array['zone_id'] = $_POST['zone_id'];
    $edit_array['srate'] = $_POST['srate'];
    $edit_array['lrate'] = $_POST['lrate'];
    $edit_array['author'] = $user . "(" .$role.")";

    check_customer($edit_array,'update');
}

?>

<section class="container">
    <div class="container">
        <h2 class="w3-center"> CUSTOMERS REGISTERED TO ISEOLUWA VENTURES</h2>
        <hr>
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <input type="search" class="my-form w3-border-blue-grey" id="searchForm" placeholder="Search By Card No, Name"/>
                    <i id="searchBtn" class="fa fa-search" style="color:#f13c20" ></i>
                    <!-- <button id="searchBtn" class="btn btn-danger" type="submit">Go!</button> -->
                </div>
                <div class="col-md-5">
                    Filter by Zone
                    <form method='get'>
                    <select name='zone' id='' class='form-control br-0'>
                    <option selected value> All  </option>
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
                    <button class="btn btn-dark" type='submit'>GO!</button>
                    </form>
                </div>
                <div class="col-md-2">
                    <a data-toggle="modal" href="#addModal"><button class="btn my-button w3-white w3-border-blue-grey">ADD NEW CUSTOMER <i style="color:#f13c20" class="fa fa-plus"></i></button></a>
                </div>
            </div>
        </div>
        <hr>
        <table class="table table-bordered my-table">
            <h3>ZONE : <?php echo $selectZone;?></h3>
            <h3>CUSTOMERS : <?php echo $customer_count;?></h3>
            <tr>
                <th>S/N</th>
                <th>CARD NUMBER</th>
                <th>NAME</th>
                <th>PHONE NUMBER</th>
                <th>REGISTRATION DATE</th>
                <th>ZONE</th>
                <th>VIEW/EDIT/DELETE</th>
            </tr>
            <?php
        while ($customer_rows = mysqli_fetch_assoc($customer_result)) {
            echo 
            "<tr>
            <td>". $count ."</td>
            <td>". $customer_rows['card_no'] ."</td>
            <td>" . $customer_rows['customer_name'] . "</td>
            <td>" . $customer_rows['customer_phone_num'] . "</td>
            <td>" . $customer_rows['reg_date'] . "</td>
            <td>" . $customer_rows['zone'] . "</td>
            <td style='text-align:center'> 
            <a title='View Customer Details' href='/customer.php?custNo=". $customer_rows['card_no'] ."'<i class='fa fa-external-link click-btn view'></i></a> 
            <a title='Edit Customer Details' data-toggle=\"modal\" href=\"#editJobModal". $customer_rows['card_no'] ."\"><i class='fa fa-pencil click-btn edit'></i></a> 
            <i class='fa fa-close click-btn delete'></i> </td>
            </tr>

            <div class='modal fade' id='editJobModal". $customer_rows['card_no'] ."' tabindex='-1' role='dialog' aria-labelledby='editJobModalLabel' data-backdrop='false' aria-hidden='true'>
                <div class='modal-dialog ' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header bg-light'>
                        <!-- Create Job Modal-->
                            <h5 class='modal-title' id='editJobModalLabel'>Customer #". $customer_rows['card_no'] ."</h5>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    <div class='modal-body'>
                        <div class='nrm-wrapper'>
                        <form method='post'>
                            <div class='form-group required'>
                                <label for='card_no'>Card No</label>
                                <input required type='text' name='card_no' value='". $customer_rows['card_no'] . "' class='form-control br-0'>
                            </div>
                            <div class='form-group required'>
                                <label for='cust_name'>Customer Name</label>
                                <input required type='text' name='cust_name' value='". $customer_rows['customer_name'] . "' class='form-control br-0'>
                            </div>
                            <div class='form-group'>
                                <label for='cust_no'>Phone Number</label>
                                <input type='number' name='cust_no' value='". $customer_rows['customer_phone_num'] . "' class='form-control br-0'>
                            </div>
                            <div class='row'>
                                <div class='col-lg-6'>
                                    <div class='form-group required'>
                                        <label for='srate'>Daily Savings Rate (NGN)</label>
                                        <input required type='number' id='srate' name='srate' class='form-control br-0' value='".$customer_rows['savings_rate']."'>
                                    </div>
                                </div>
                                <div class='col-lg-6'>
                                    <div class='form-group required'>
                                        <label for='lrate'>Daily Loan Rate (NGN)</label>
                                        <input required type='number' id='lrate' name='lrate' class='form-control br-0' value='".$customer_rows['loan_rate']."'>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-lg-6'>
                                    <div class='form-group'>
                                        <label for='zone'>Zone</label>
                                        <select name='zone' id='' class='form-control br-0'>";
                                            foreach ($zones as $zone) {
                                                if ($zone == $customer_rows['zone']) {
                                                    echo "<option selected>".$zone."</option>";
                                                }
                                                else {
                                                    echo "<option>".$zone."</option>";
                                                }
                                            };
                                       echo "</select>
                                    </div>
                                </div>
                                <div class='col-lg-6'>
                                    <div class='form-group'>
                                        <label for='reg_date'>Registration Date</label>
                                        <input name='reg_date' value='" . $customer_rows['reg_date'] . "' class='form-control br-0'>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-12'>
                                    <input type='hidden' name='customerID' value='".$customer_rows['customer_id']."'>
                                    <input type='submit' name='editSubmit' class='submit btn btn-info b-7 br-0 btn-block' value='Save Changes'>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
              </div>
            </div>
            </div>";
            $count++;
        }
            ?>
        </table>

        <div class='modal fade' id='addModal' tabindex='-1' role='dialog' aria-labelledby='editJobModalLabel' aria-hidden='true'>
                <div class='modal-dialog ' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header bg-light'>
                        <!-- Add Customer Modal-->
                            <h5 class='modal-title' id='addModalLabel'>ADD A NEW CUSTOMER</h5>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                    <div class='modal-body'>
                        <div class='nrm-wrapper'>
                            <form method="post">
                            <div class='form-group required'>
                                <label for="card_no">Card No</label>
                                <input required type='text' name='card_no' class='form-control br-0' placeholder="Card Number">
                            </div>
                            <div class='form-group required'>
                                <label for='cust_name'>Customer Name</label>
                                <input required type='text' name='cust_name' class='form-control br-0' placeholder="Customer Name">
                            </div>
                            <div class='form-group'>
                                <label for='cust_no'>Phone Number</label>
                                <input step="any" name='cust_no' class='form-control br-0' placeholder="e.g 09011111111">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group required">
                                        <label for="srate">Daily Savings Rate (NGN)</label>
                                        <input required type="number" id="srate" name='srate' class='form-control br-0'>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group required">
                                        <label for="lrate">Daily Loan Rate (NGN)</label>
                                        <input required type="number" id="lrate" name='lrate' class='form-control br-0' value="0">
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-lg-6'>
                                    <div class='form-group'>
                                        <label for='zone'>Zone</label>
                                        <select name='zone' id='' class='form-control br-0'>
                                        <?php
                                            for ($i=0; $i < count($zones) ; $i++) { 
                                                if ($i == 0) {
                                                    echo "<option selected>".$zones[$i]."</option>";
                                                }
                                                else {
                                                    echo "<option>".$zones[$i]."</option>";
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-lg-6'>
                                    <div class='form-group'>
                                        <label for='reg_date'>Registration Date</label>
                                        <input type="text" class="datepicker" name='reg_date' class='form-control br-0'>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-12'>
                                    <input type='submit' name='add_submit' class='submit btn btn-info b-7 br-0 btn-block' value='Add Customer!'>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
              </div>
            </div>
            </div>

  
<?php require_once('partials/footer.php') ?>