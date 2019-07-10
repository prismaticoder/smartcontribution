<?php 
require_once('partials/header.php'); 

//Filters are Zone and Month. Simple!

$result = exec_query(
    "SELECT main_customers.customer_id,main_customers.card_no,main_customers.customer_name,main_customers.reg_date,main_customers.loan_rate,main_customers.savings_rate,main_customers.loan_collected,main_customers.balance,zone.zone 
    FROM `main_customers` 
    INNER JOIN `zone` 
    ON main_customers.zone_id = zone.zone_id
    ORDER BY main_customers.customer_name ASC"
    );

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

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
        <!-- <div class="form-group">
                <label for="month">Choose A Month</label>    
                    <select name='month' id='month' class='form-control'>
                        <option selected value> -- </option>
                        <option value="">January</option>
                        <option value="">February</option>
                        <option value="">March</option>
                        <option value="">April</option>
                        <option value="">May</option>
                        <option value="">June</option>
                        <option value="">July</option>
                        <option value="">August</option>
                        <option value="">September</option>
                        <option value="">October</option>
                        <option value="">November</option>
                        <option value="">December</option>
                        </select>
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
                <button id="balance_filter" class="btn btn-primary">Filter Table!</button>
        </div>
        <div class="col-md-9">
            <table class="table table-bordered table-responsive table-hover" id="dataTable">

                <thead>
                <tr>

                <th>S/N</th>
                <th>CARD NO</th>
                <th>NAME</th>
                <th>ZONE</th>
                <th>REGISTRATION DATE</th>
                <th>S.RATE</th>
                <th>L.RATE</th>
                <th>LOAN BALANCE</th>
                <th>SAVINGS BALANCE</th>

                </tr>
                </thead>
                <tbody id="balanceBody">
                    <?php
                    
                    $count = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "
                            <tr>
                            <td>".$count."</td>
                            <td>".$row['card_no']."</td>
                            <td>".$row['customer_name']."</td>
                            <td>".$row['zone']."</td>
                            <td>".$row['reg_date']."</td>
                            <td>".$row['savings_rate']."</td>
                            <td>".$row['loan_rate']."</td>
                            <td>".-$row['loan_collected']."</td>
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
</div>



<?php require_once('partials/footer.php')?>