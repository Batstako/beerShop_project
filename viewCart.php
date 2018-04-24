<?php

require_once 'cart.php';
$cart = new Cart;
if (isset($_SESSION['id']) || isset($_COOKIE['remember_me'])) {
    $username = $_SESSION['user'];

    $sql = "SELECT
            *
        FROM
            users
        WHERE
             username = ?

         ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$username]);

    $user = $stmt->fetch();
}
else{
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cart</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>
    <link type="text/css" rel="stylesheet" media="screen" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <style>
        .container{padding: 50px;}
        input[type="number"]{width: 20%;}
    </style>
    <script>
        function updateCartItem(obj,id){
            $.get("cartAction.php", {action:"updateCartItem", id:id, qty:obj.value}, function(data){
                console.log(data);
                if(data == '1'){
                    location.reload();
                }else{
                    alert('Cart update failed, please try again.');
                    location.reload();

                }
            });
        }
    </script>
</head>
<body>
<header>
    <?php include_once "php_includes/header.php"; ?>
</header>
<div class="container" style="margin-top:100px; margin-bottom: 100px;">
    <h1>Basket</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>&nbsp;</th>
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
                    <td><input type="number" class="form-control text-center" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')"></td>
                    <td><?php echo '$'.$item["subtotal"].' lv'; ?></td>
                    <td>
                        <a href="cartAction.php?action=removeCartItem&id=<?php echo $item["rowid"]; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
            <?php } }else{ ?>
        <tr><td colspan="5"><p>Your cart is empty.....</p></td>
            <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <td><a href="catalog.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continue Shopping</a></td>
            <td colspan="2"></td>

            <?php if($cart->total_items() > 0){ ?>
                <td class="text-center"><strong>Total <?php echo ''.$cart->total().' lv'; ?></strong></td>
<!--                <td class="text-center"><strong>Wallet --><?php //echo $user['wallet'] .' lv' ?><!--</strong></td>-->
<!--                --><?php
//                if($cart->total() > $user['wallet'])
//                    echo "<div class='alert alert-danger'> You don't have enough funds in your account to make this order</div>";
//
//                else{ ?>
                <td><a href="checkout.php" class="btn btn-success btn-block">Checkout <i class="glyphicon glyphicon-menu-right"></i></a></td>
<!--                --><?php //} ?>
            <?php } ?>
        </tr>
        </tfoot>
    </table>
</div>
<footer class="footer navbar-fixed-bottom">
    <?php include_once "php_includes/footer.php"; ?>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
