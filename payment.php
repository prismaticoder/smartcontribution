<?php 
require_once('partials/header.php'); 
?>


<h1>BEGIN WORK!!</h1>
<section class="container">
    <div class="row">
    <h1>NEW WORD ALERT!!!</h1>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <form method="get">
                <div class="form-group">
                    <input type="search" name="custNo" placeholder="Search Customer Number">
                    <button type="submit" class="btn btn-block">Search!</button>
                </div>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
</section>

<?php require_once('partials/footer.php'); ?>