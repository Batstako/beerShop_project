<?php
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
    <title>Edit Beer</title>

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
<div class="container" style="margin-top: 50px; margin-bottom: 50px;">

    <div class="page-header">
        <h1>Edit Product</h1>
    </div>

    <?php
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');



    $query = "SELECT o.order_id as order_id, o.product_id, o.quantity, p.name FROM order_detail o
                JOIN products p on p.id=o.product_id WHERE o.order_id = {$id} ORDER by order_id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0) {
        echo "<div class='row justify-content-md-center'>";
//            echo "<div class='col-lg-1'></div>";
        echo "<div class='table-responsive col-lg-10'>";
        echo "<table id='beerTable' class='table table-hover '>";
        echo "<thead>";
        echo "<tr class='bg-warning'>";
        //        echo "<th class='col-sm-1'>ID</th>";
        echo "<th class='col-sm-6'>Beer</th>";
        echo "<th class='col-sm-6'>Quantity</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<tr>";
            //            echo "<td>{$id}</td>";
            echo "<th class='align-middle'>{$name}</th>";
            echo "<td class='align-middle'>{$quantity}</td>";
            echo "<td class='align-middle'>";
            echo "<div class='row justify-content-md-center align-middle'>";
            echo "</div>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";

        echo "</table>";
    }
        ?>

</div> <!-- end .container -->
<footer class="footer navbar-fixed-bottom">
    <?php include_once "php_includes/footer.php"; ?>
</footer>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

</body>
</html>