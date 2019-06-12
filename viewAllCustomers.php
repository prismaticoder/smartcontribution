<?php 
require_once('partials/header.php'); 

//Indicate that login is required to view this particular page

$customer_result = exec_query(
    "SELECT main_customers.customer_id,main_customers.customer_name,main_customers.customer_phone_num,main_customers.reg_date,zone.zone 
    FROM `main_customers` 
    INNER JOIN `zone` 
    ON main_customers.zone_id = zone.zone_id"
    );

$count = 1;

?>

<section class="container">
    <div class="container">
        <h2 class="w3-center"> CUSTOMERS REGISTERED TO ISEOLUWA VENTURES</h2>
        <table class="table table-bordered my-table">
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
            <td>". $customer_rows['customer_id'] ."</td>
            <td>" . $customer_rows['customer_name'] . "</td>
            <td>" . $customer_rows['customer_phone_num'] . "</td>
            <td>" . $customer_rows['reg_date'] . "</td>
            <td>" . $customer_rows['zone'] . "</td>
            <td style='text-align:center'> 
            <a href='/customer.php?custNo=". $customer_rows['customer_id'] ."'<i class='fa fa-external-link click-btn view'></i></a> 
            <a data-toggle=\"modal\" href=\"#editJobModal". $customer_rows['customer_id'] ."\"><i class='fa fa-pencil click-btn edit'></i></a> 
            <i class='fa fa-close click-btn delete'></i> </td>
            </tr>
            <div class='modal fade' id='editJobModal". $customer_rows['customer_id'] ."' tabindex='-1' role='dialog' aria-labelledby='editJobModalLabel' aria-hidden='true'>
            <div class='modal-dialog ' role='document'>
              <div class='modal-content'>
                <div class='modal-header bg-light'>
                  <!-- Create Job Modal-->
                  <h5 class='modal-title' id='editJobModalLabel'>Customer #". $customer_rows['customer_id'] ."</h5>
                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button>
                </div>
                <div class='modal-body'>
                  <div class='nrm-wrapper'>
                    <div class='form-group'>
                        <label for='card_no'>Card No</label>
                      <input type='text' name='card_no' value='". $customer_rows['customer_id'] . "' class='form-control br-0'>
                    </div>
                    <div class='form-group'>
                    <label for='cust_name'>Customer Name</label>
                      <input type='text' name='cust_name' value='". $customer_rows['customer_name'] . "' class='form-control br-0'>
                    </div>
                    <div class='form-group'>
                    <label for='cust_no'>Phone Number</label>
                      <input type='number' name='cust_no' value='". $customer_rows['customer_phone_num'] . "' class='form-control br-0'>
                    </div>
                    <div class='row'>
                      <div class='col-lg-6'>
                        <div class='form-group'>
                    <label for='zone'>Zone</label>
                    <select name='zone' id='' class='form-control br-0'>
                        <option selected>". $customer_rows['zone'] . "</option>
                        <option value=''>Junior</option>
                        <option value=''>Intermidiate</option>
                        <option value=''>Expert</option>
                      </select>
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
                        <input type='submit' class='submit btn btn-info b-7 br-0 btn-block' value='Save Changes'>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>";
            $count++;
        }
            ?>
        </table>

  
<?php require_once('partials/footer.php') ?>