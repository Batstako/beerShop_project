<?php

// include database configuration file
include_once 'connect.php';

// initializ shopping cart class
require_once 'cart.php';
$cart = new Cart;
if (isset($_SESSION['id'])) {

}
else{
    header('Location: login.php');
    exit;
}
// redirect to home if cart is empty
if($cart->total_items() <= 0){
    header("Location: index.php");
}

// set customer ID in session
$_SESSION['sessCustomerID'] = $_SESSION['id'];

// get customer details by session customer ID
$query = "SELECT * FROM users WHERE id = ".$_SESSION['sessCustomerID'];
$stmt = $pdo->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$errors = array (
    1 => "You don't have enough funds in your account to make this order!",

);

$error_id = isset($_GET['err']) ? (int)$_GET['err'] : 0;
$error_quantity = isset($_GET['info']) ? $_GET['info'] : '';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>
    <link type="text/css" rel="stylesheet" media="screen" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .container{width: 100%;padding: 50px;}
        .table{width: 65%;float: left;}
        .shipAddr{width: 30%;float: left;margin-left: 30px;}
        .footBtn{width: 95%;float: left;}
        .orderBtn {float: right;}
    </style>
</head>
<body>
<header class="fixed-top">
    <?php include_once "php_includes/header.php"; ?>
</header>
<div class="container" style="margin-top:100px;">
 <?php if ($error_id != 0) {
     echo  "<div class='alert alert-danger'> You don't have enough funds in your account to make this order! </div>";
 } ?>
    <?php if ($error_quantity != '') {
        $Url = $_GET['err'];
        $UrlQuantity = $_GET['quantity'];
        $UrlId = $_GET['id'];

        $beerQuery = "SELECT * FROM products WHERE id =" .$UrlId;
        $beerStmt = $pdo->prepare($beerQuery);
        $beerStmt->execute();
        $beerRow = $beerStmt->fetch(PDO::FETCH_ASSOC);

        echo  "<div class='alert alert-danger'> We don't have {$UrlQuantity} of {$Url}. We currently have {$beerRow['quantity']} in stock. </div>";
    } ?>
    <h1>Order Preview</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($cart->total_items() > 0){
            //get cart items from session
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
                ?>
                <tr>
                    <td><?php echo $item["name"]; ?></td>
                    <td><?php echo '$'.$item["price"].' lv'; ?></td>
                    <td><?php echo $item["qty"]; ?></td>
                    <td><?php echo '$'.$item["subtotal"].' lv'; ?></td>
                </tr>
            <?php } }else{ ?>
        <tr><td colspan="4"><p>No items in your cart......</p></td>
            <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3"></td>
            <?php if($cart->total_items() > 0){ ?>
                <td class="text-center"><strong>Total <?php echo '$'.$cart->total().' lv'; ?></strong></td>
            <?php } ?>
        </tr>
        </tfoot>
    </table>
    <div class="shipAddr">
        <h4>Shipping Details</h4>
        <p>First Name: <?php echo $row['first_name']; ?></p>
        <p>Last Name: <?php echo $row['last_name']; ?></p>
        <p>Phone: <?php echo $row['phone']; ?></p>
        <p>Address: <?php echo $row['address']; ?></p>
    </div>
    <div class="footBtn">
        <a href="catalog.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continue Shopping</a>
        <a href="cartAction.php?action=placeOrder" class="btn btn-success orderBtn">Place Order <i class="glyphicon glyphicon-menu-right"></i></a>
        <a href="viewCart.php" class="btn btn-warning">Back to cart</a>
    </div>


</div>
<footer class="footer navbar-fixed-bottom">
    <?php include_once "php_includes/footer.php"; ?>
</footer>
</body>
</html>
