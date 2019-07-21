<?php 
require_once('partials/header.php');

?>

<section class="container-fluid">
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


</section>

<?php require_once('partials/footer.php'); ?>