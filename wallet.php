<?php
/**
 * Created by PhpStorm.
 * User: paleksandrov
 * Date: 18.4.2018 г.
 * Time: 9:23
 */
?>
<?php
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
                    WHERE id=:id";


    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':wallet', $wallet);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Record was updated.</div>";
    }

    header("Location: wallet.php");
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>

    <title>Personal wallet</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" media="screen"
          href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link href="css/styles.css" rel="stylesheet">
</head>


<body class="text-center" style="background-color:#eee">

<header class="fixed-top">
    <?php include_once "php_includes/header.php"; ?>
</header>


<div class="container" style="margin-top: 150px; margin-bottom: 150px;">
<h1>Personal wallet</h1>
<div class='row' style="margin-top: 50px">
    <div class='col-lg-1'></div>
    <div class='col-lg-8' style='background-image: url("images/wallet_card.png"); background-repeat: no-repeat; height: 500px;'>
        <form method="post" style="margin-top: 235px">
            <table class="form-group">
                <tr>
                    <td style="color:white">Deposit:</td>
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="money" placeholder="0.00" style="width:200px"></td>
                    <td><input type="submit" class="btn-primary btn btn-success" name="deposit" value="Deposit"></td>
                </tr>
            </table>
        </form>
    </div>
    <div class='col-lg-3' style="margin-top: 230px"><h3 id="money"> Current balance: BGN <?= $user['wallet'] ?> </h3></div>
    

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

