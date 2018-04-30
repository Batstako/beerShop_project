<?php
ob_start();
require_once 'connect.php';
if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin') {

}
else{
    header('HTTP/1.0 401 Unauthorized');
    echo 'You are not authorized to be here!';
    exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Edit Order</title>

    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>
    <link type="text/css" rel="stylesheet" media="screen" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">

</head>
<body>
<header>
    <?php include_once "php_includes/header.php"; ?>
</header>
<div class="container" style="margin-top: 150px; margin-bottom: 150px;">

    <div>
        <h1>View Order</h1>
    </div>

    <?php
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
    if (isset($_POST['status'])) {
        $orderStatus = $_POST['status'];
    }
    echo "    
           <div class=''>
                <div class='col-lg-1'></div>
                <a href='orders.php' class='btn btn-primary mb-3'>Back to orders</a>
          </div>";

    // ORDER INFORMATION

    $query = "SELECT o.order_id as order_id, o.product_id, o.quantity, p.name, p.price FROM order_detail o
                JOIN products p on p.id=o.product_id WHERE o.order_id = {$id} ORDER by order_id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0) {
        echo "<div class='row justify-content-md-center'>";
//            echo "<div class='col-lg-1'></div>";
        echo "<div class='table-responsive col-lg-10 view_order_table' style='padding:0px'>";
        echo "<table id='beerTable' class='table table-hover'>";
        echo "<thead>";
        echo "<tr class='bg-warning'>";
        //        echo "<th class='col-sm-1'>ID</th>";
        echo "<th class='col-sm-6'>Beer</th>";
        echo "<th class='col-sm-2'>Quantity</th>";
        echo "<th class='col-sm-2'>Single Price</th>";
        echo "<th class='col-sm-2'>Total Price</th>";
        echo "<th class='col-sm-1'></th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        $orderPrice = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<tr>";
            //            echo "<td>{$id}</td>";
            $totalPrice = $price * $quantity;
            $orderPrice += $totalPrice;
            $totalPrice = number_format((float)$totalPrice, 2, '.', '');
            echo "<th class='align-middle'>{$name}</th>";
            echo "<td class='align-middle'>{$quantity}</td>";
            echo "<td class='align-middle'>{$price} lv</td>";
            echo "<td class='align-middle'>{$totalPrice} lv</td>";
            echo "<td class='align-middle'>";
            echo "<div class='row justify-content-md-center align-middle'>";
            echo "</div>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";

        echo "</table>";
        echo "<p style='float: right; '> Total order price: {$orderPrice} lv </p>";
        echo "</div>";
        echo "</div>";
    }
    // USER INFORMATION
    $userQuery = "SELECT o.id as order_id, o.date, o.total_price, u.first_name, u.username, u.last_name, u.address, u.phone, u.email, o.status FROM orders o
                  JOIN users u on u.id=o.user_id WHERE o.id = {$_GET['id']}";
    $userStmt = $pdo->prepare($userQuery);
    $userStmt->execute();
    $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);
    $fullName = $userRow['first_name'] . ' ' . $userRow['last_name'];

    echo "<div style='margin-top: 50px;'>";
    echo "    <h1 style='z-index:-9999'>User Information</h1>";
    echo "</div>";
    echo "<div class='row justify-content-md-center' >";
    //            echo "<div class='col-lg-1'></div>";
    echo "<div class='table-responsive col-lg-10 view_order_table' style='padding:0px'>";
    echo "<table id='beerTable' class='table table-hover'>";
    echo "<thead>";
    echo "<tr class='bg-warning'>";
    //        echo "<th class='col-sm-1'>ID</th>";
    echo "<th class='col-sm-2'>Username</th>";
    echo "<th class='col-sm-3'>Name</th>";
    echo "<th class='col-sm-3'>Address</th>";
    echo "<th class='col-sm-2'>Phone</th>";
    echo "<th class='col-sm-2'>Email</th>";
    echo "<th class='col-sm-1'></th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    echo "<tr>";

            echo "<th class='align-middle'>{$userRow['username']}</th>";
            echo "<th class='align-middle'>{$fullName}</th>";
            echo "<td class='align-middle'>{$userRow['address']}</td>";
            echo "<td class='align-middle'>{$userRow['phone']}</td>";
            echo "<td class='align-middle'>{$userRow['email']}</td>";
            echo "<td class='align-middle'>";
            echo "<div class='row justify-content-md-center align-middle'>";
            echo "</div>";
            echo "</td>";
            echo "</tr>";
        echo "</tbody>";
        echo "</table>";

    // STATUS OF ORDER
    $statusQuery = "SELECT * FROM orders WHERE id = {$_GET['id']}";
    $statusStmt = $pdo->prepare($statusQuery);
    $statusStmt->execute();
    $row = $statusStmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3 style='text-align: center'>Change order status</h3>";
    echo "<div style='text-align: center; margin-bottom: 25px;'>";
    echo    "<form name='sort' action='viewOrder.php?id={$_GET['id']}' method='post'>";
    echo    "<select class='btn' name='status'>";
    echo        "<option>Status: {$row['status']}</option>";
    echo        "<option value='processing'>Processing</option>";
    echo        "<option value='completed'>Completed</option>";
    echo    "</select>";
    echo    "<input class='btn btn-danger' type='submit' name='submit' value='Change Status'/>";
    echo    "</form>";
    echo "</div>";
    $changeQuery = $statusQuery;

    if (isset($_POST['status']) && $orderStatus == 'processing') {
        $changeQuery = "UPDATE orders SET status = 'processing' where id = {$_GET['id']}";
    }

    else if (isset($_POST['status']) && $orderStatus == 'completed') {
        $changeQuery = "UPDATE orders SET status = 'completed' where id =  {$_GET['id']}";
    }

    $changeStmt = $pdo->prepare($changeQuery);
    $changeStmt->execute();
    if(isset($_POST['submit'])){
        header("Location: viewOrder.php?id={$_GET['id']}");
    }
    ob_end_flush();


    //"UPDATE users SET loggedin = '1' where `id` = $userid ";
        ?>

</div> <!-- end .container -->
<footer class="footer navbar-fixed-bottom">
    <?php include_once "php_includes/footer.php"; ?>
</footer>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

</body>
</html>