<?php
session_start();
if (!isset($_POST['product_id']) || !isset($_POST['product_total'])) {
    die("Invalid checkout request.");
}
?>
<?php include 'config.php'; ?>
<?php include 'header.php'; ?>

<div class="product-cart-container">
    <div class="container">
        <div class="clearfix " style="width:60%;margin:auto">
            <div class="card">
                <div class="card-body">

                    <div class="cost-box"
                        style="padding:5px; border-top:1px solid gray; border-radius:13px 13px 0px 0px; box-shadow: gray 0px 12px 12px 1px">
                        <br>
                        <h3 class="mb-4 text-center paymentMethod">Select Payment Method</h3>
                        <hr>
                        <br>
                        <div class="row">
                            <p class="col-md-6 text-left">PRODUCT QUANTITY</p>
                            <p class="col-md-6 text-right"><strong><?php echo $_POST['product_qty']; ?></strong></p>
                        </div>
                        <br>
                        <div class="row">
                            <p class="col-md-6 text-left">PRODUCT AMOUNT</p>
                            <p class="col-md-6 text-right"><strong><?php echo $_POST['product_total'] - 200; ?></strong>
                            </p>
                        </div>
                        <br>
                        <div class="row">
                            <p class="col-md-6 text-left">SHIPPING COST</p>
                            <p class="col-md-6 text-right"><strong><?php echo 200; ?></strong></p>
                        </div>
                        <br>
                        <div class="row">
                            <p class="col-md-6 text-left">NET AMOUNT</p>
                            <p class="col-md-6 text-right"><strong><?php echo $_POST['product_total'] ?></strong></p>
                        </div>
                        <br>
                        <div class="row">
                            <p class="col-md-6 text-left">DELIVERY TIME</p>
                            <p class="col-md-6 text-right"><strong>3 to 4Pc working days</strong></p>
                        </div>
                        <br>
                        <!-- <p class="gift text-primary text-center">Got a gift card or a promotional
                            code?</p> -->
                    </div>



                    <br>
                    <form action="xpay.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $_POST['product_id']; ?>">
                        <input type="hidden" name="product_total" value="<?php echo $_POST['product_total']; ?>">
                        <input type="hidden" name="product_qty" value="<?php echo $_POST['product_qty']; ?>">
                        <button type="submit" class="btn btn-primary btn-block"
                            style="box-shadow: gray 0px 12px 12px 1px">Pay Now</button>
                    </form>
                    <br>
                    <form action="cod.php" method="POST" class="mt-3">
                        <input type="hidden" name="product_id" value="<?php echo $_POST['product_id']; ?>">
                        <input type="hidden" name="product_total" value="<?php echo $_POST['product_total']; ?>">
                        <input type="hidden" name="product_qty" value="<?php echo $_POST['product_qty']; ?>">
                        <button type="submit" class="btn btn-secondary btn-block"
                            style="box-shadow: gray 0px 12px 12px 1px">Cash on Delivery</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>