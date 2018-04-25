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
<!doctype html>
<html lang="en">
<head>

    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>
    <link type="text/css" rel="stylesheet" media="screen" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Panel</title>


</head>


<body style="background-color:#eee">

<header class="fixed-top">
    <?php include_once "php_includes/header.php"; ?>
</header>

<div class="container" style="margin-top: 100px; margin-bottom: 150px;">
        <div class="row justify-content-md-center my-5">
            <div class="col my-5">
                <h2 class="text-center">Admin panel</h2>
            </div>
        </div>
        <div class="container my-5 justify-content-md-center">
            <div class="row my-5 justify-content-md-center" >
                    <div class="col-lg-3 text-center my-5">
                        <a href="addBeer.php">
                            <img class="img-fluid" src="images/add_beer.png">
                            <p class="text-center">ADD BEER</p>
                        </a>
                    </div>

                    <div class="col-lg-3 text-center my-5">
                        <a href="listAllBeers.php">
                            <img class="img-fluid"  src="images/edit_beer.png">
                            <p class="text-center">EDIT BEER</p>
                        </a>
                    </div>

                    <div class="col-lg-3 text-center my-5">
                        <a href="orders.php">
                            <img class="img-fluid"  src="images/user_acc.png">
                            <p class="text-center">USER ORDERS</p>
                        </a>
                    </div>
            </div>
        </div>
</div>




<footer class="container fixed-bottom">
    <?php include_once "php_includes/footer.php"; ?>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("#profile").addClass('text_shadow');
    });
</script>

</body>

</html>