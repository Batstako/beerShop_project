<?php

require_once 'cart.php';
$cart = new Cart;
require_once 'connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['user'];
$id = $_SESSION['id'];
$sql = "SELECT
            wallet
        FROM
            users
        WHERE
             id = ?

         ";

$stmt = $pdo->prepare($sql);

$stmt->execute([$id]);

$user = $stmt->fetch();

if (isset($_POST['deposit'])) {
    $deposit = htmlspecialchars(strip_tags($_POST['money']));
    $wallet = floatval($deposit) + floatval($user['wallet']);

    $query = "UPDATE users 
                    SET wallet=:wallet
                    WHERE username=:username";


    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':wallet', $wallet);
    $stmt->bindParam(':username', $username);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Record was updated.</div>";
    }

    header("Location: wallet.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Basket</title>
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

<div class="container" style="margin-top:50px; margin-bottom: 100px;">
    <h1>Basket</h1>
    <div style="position:fixed; margin-top: -50px; margin-left: 55%;"><h4 id="money" style="vertical-align: middle; display: inline-block;">Current balance: <?= $user['wallet'] ?> BGN</h4> <img id="wallet" src="images/wallet_card.png" style="width: 20%; cursor: pointer" /></div>
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
                    <td style="vertical-align: middle;"><?php echo $item["name"]; ?></td>
                    <td style="vertical-align: middle;"><?php echo $item["price"].' lv'; ?></td>
                    <td><input type="number" class="form-control text-center" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')"></td>
                    <td style="vertical-align: middle;"><?php echo $item["subtotal"].' lv'; ?></td>
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
<script>
    $(document).ready(function () {
        $('#wallet').click(function () {
            window.location.href = this.id + '.php';
        });
    });
</script>
</body>
</html>
