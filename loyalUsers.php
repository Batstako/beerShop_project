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
    <title>Top 5 Users</title>

    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>
    <link type="text/css" rel="stylesheet" media="screen" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">

</head>
<body style="background-color:#eee;">
<header class="fixed-top">
    <?php include_once "php_includes/header.php"; ?>
</header>

<!-- container -->
<div class="container" style="margin-top: 100px;">
    <div class="row justify-content-md-center">
        <div class="page-header col-lg-10 text-center">
            <h2>Top 5 Users</h2>
        </div>
    </div>
    <?php
        $query = "SELECT * FROM ( SELECT * FROM users ORDER BY total_spent DESC LIMIT 5) as u ORDER BY total_spent DESC";

    echo "    
           <div class='row'>
                <div class='col-lg-1'></div>
                <a href='orders.php' class='btn btn-primary mb-3 ml-4'>Back to orders</a>
          </div>";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $num = $stmt->rowCount();

    if($num>0){
        echo "<div class='row justify-content-md-center'>";
//            echo "<div class='col-lg-1'></div>";
        echo "<div class='table-responsive col-lg-10'>";
        echo "<table id='beerTable' class='table table-hover '>";
        echo "<thead>";
        echo "<tr class='bg-warning'>";
        //        echo "<th class='col-sm-1'>ID</th>";
        echo "<th class='col-sm-3'>Username</th>";
        echo "<th class='col-sm-3'>Address</th>";
        echo "<th class='col-sm-2'>Phone</th>";
        echo "<th class='col-sm-2'>Total Spent</th>";
        echo "<th class='col-sm-2'>Points</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $points = $total_spent / 10;
            echo "<tr>";
            //            echo "<td>{$id}</td>";
            echo "<th class='align-middle'>{$username}</th>";
            echo "<td class='align-middle' style='text-align: justify; word-break: break-all;'>{$address}</td>";
            echo "<td class='align-middle'>{$phone}</td>";
            echo "<td class='align-middle'>{$total_spent}</td>";
            echo "<td class='align-middle'>{$points}</td>";
            echo "<td class='align-middle'>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";

        echo "</table>";

        echo "</div>";
        echo "<div class='col-lg-10' style='margin-bottom: 150px'>";
        echo "</div>";
//            echo "<div class='col-lg-1'></div>";
        echo "</div>";
    }

    else{
        echo "<div class='alert alert-danger'>No records found.</div>";
    }
    ?>

</div>
<footer class="footer navbar-fixed-bottom">
    <?php include_once "php_includes/footer.php"; ?>
</footer>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!--<script type="text/javascript" href="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>-->
<script type='text/javascript'>
    $(document).ready(function() {
        $("#profile").addClass('text_shadow');

        // $('#beerTable').DataTable();

    });
    function delete_user( id ){
        var answer = confirm('Are you sure?');
        if (answer){
            window.location = 'delete.php?id=' + id;
        }
    }

</script>

</body>
</html>
