<?php
require_once 'connect.php';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
// beers per page
$records_per_page = 12;
$from_record_num = ($records_per_page * $page) - $records_per_page;

?>


<?php
require_once 'connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['user'];
$sql = "SELECT
            wallet
        FROM
            users
        WHERE
             username = ?

         ";

$stmt = $pdo->prepare($sql);

$stmt->execute([$username]);

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
<!doctype html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>
    <link type="text/css" rel="stylesheet" media="screen" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link href="css/catalog.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.2.4/css/simple-line-icons.min.css'>

    <link href="css/styles.css" rel="stylesheet">

    <title>Catalog</title>
</head>

<body style="background-color:#eee; margin-top: 50px;">

<header class="fixed-top">
    <?php include_once "php_includes/header.php"; ?>
</header>

<div style=" padding-top: 100px;
  text-align: center;">
  <div style="position:fixed; margin-left: 70%;"><h3 id="money" style="vertical-align: 0.7em; display: inline-block;">Current balance: <?= $user['wallet'] ?> BGN</h3> <img src="images/wallet_card.png" style="width: 10%;" /></div>
    <form name="sort" action="catalog.php" method="get">
        <select class="btn" name="order">
            <option>Order by:</option>
            <option value="name">Name (A-Z)</option>
            <option value="nameDesc">Name (Z-A)</option>
            <option value="price">Price (Low > High)</option>
            <option value="priceDesc">Price (High > Low)</option>
        </select>
        <input class="btn btn-danger" type="submit" value=" - Sort - "/>        
    </form>
</div>

<div class='container' style="margin-bottom: 150px; text-align: center;">

    <?php

    if (isset($_GET['order'])) {
        $sortCriteria = $_GET['order'];
        $test = "order=" . $sortCriteria;
    } else {
        $test = "";
    }

    if (isset($_GET['order']) && $sortCriteria == 'name') {
        $query = "SELECT id, name, description, price, picture, quantity FROM products ORDER BY name ASC LIMIT :from_record_num, :records_per_page";
        // $query = "SELECT  id,name, description, price, quantity FROM products ORDER BY name ASC";


    } elseif (isset($_GET['order']) && $sortCriteria == 'nameDesc') {
        $query = "SELECT id, name, description, price, picture, quantity FROM products ORDER BY name DESC LIMIT :from_record_num, :records_per_page";
        // $query = "SELECT  id,name, description, price, quantity FROM products ORDER BY name DESC";

    } elseif (isset($_GET['order']) && $sortCriteria == 'price') {
        $query = "SELECT id, name, description, price, picture, quantity FROM products ORDER BY price ASC LIMIT :from_record_num, :records_per_page";
        //$query = "SELECT  id,name, description, price, quantity FROM products ORDER BY price ASC";

    } elseif (isset($_GET['order']) && $sortCriteria == 'priceDesc') {

        $query = "SELECT id, name, description, price, picture, quantity FROM products ORDER BY price DESC LIMIT :from_record_num, :records_per_page";
        // $query = "SELECT  id,name, description, price, quantity FROM products ORDER BY price DESC";

    } else {
        $query = "SELECT id, name, description, price, picture, quantity FROM products ORDER BY id DESC
    LIMIT :from_record_num, :records_per_page";
    }


    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
    $stmt->execute();

    echo "";
    $num = $stmt->rowCount();
    if ($num > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<div class='product justify-content-md-center'>";
            echo "<img class='beerPicture' src='beers/{$picture}' style='height: 130px; width:130px'>";
            echo "<h2 class='header my-3 text-truncate'>{$name}</h2>";
            echo "<p class='description' style='display: none;'>{$description}</p>";
            echo "<p class='price'>{$price} lv.</p>";
            if ($quantity == 0) {
                echo "<h2 class='text_shadow'>Out of Stock</h2>";
            } else {
                //echo "<div class='btn'>Add to cart</div>";
                $cartId = $row["id"];
                echo "<a class='btn' href='cartAction.php?action=addToCart&id=$cartId'>Add to cart</a>";

            }
            echo "<div class='quickview'>Description</div>";
            echo "</div>";
        }
    }


    echo "<div class='quickviewContainer' style='margin-top: 50px;'>";
        echo "<div class='close'></div>";
        echo "<h2 class='headline'>{$name}</h2>";
        echo "<img class='picture' src='beers/{$picture}' style='height:30%;'>" ;
        echo "<p class='price my-3'>{$price}</p>";
        echo "<p class='description my-5 text-justify'>{$description}</p>";

    echo "</div>";

    echo "<br>";
    $query = "SELECT COUNT(*) as total_rows FROM products";
    $stmt = $pdo->prepare($query);
    // execute query
    $stmt->execute();
    // get total rows
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_rows = $row['total_rows'];
    // paginate records
    $page_url = "catalog.php?{$test}&";
    include_once "php_includes/paging.php";
    echo "</div>";
    ?>


    <footer class="fixed-bottom"
            style="font-family: 'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol'">
        <div>
            <h2 style='margin: 5px 0px 15px 0px;'>Quality House Beer</h2>
            <h6>phone: +359 123 123 123</h6>
        </div>
    </footer>

    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
    <script src="js/catalog.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $("#catalog").addClass('text_shadow');
        });

        window.onscroll = function () {
            myFunction()
        };

        var header = document.getElementById("myHeader");
        var sticky = header.offsetTop;

        function myFunction() {
            if (window.pageYOffset >= sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }
        }
    </script>

</body>
</html>
