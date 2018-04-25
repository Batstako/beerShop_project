<?php
require_once 'connect.php';
if (isset($_SESSION['user'])) {
    $error = '';
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
    <title>Edit your profile</title>

    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" media="screen" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link href="css/styles.css" rel="stylesheet">

</head>
<body>
<header>
    <?php include_once "php_includes/header.php"; ?>
</header>
<div class="container" style="margin-top: 50px; margin-bottom: 50px;">

    <?php if ($error) : ?>
        <div class="alert alert-danger">
            <strong> <?= $error ?></strong>
        </div>

    <?php endif; ?>
    <?php $error = ''; ?>

    <div class="page-header">
        <h1>Change your details</h1>
    </div>


    <?php
    $id= $_SESSION['id'];

    if($_POST){

        try{
            // prepare select query
            $query = "SELECT * FROM users WHERE id = " .$id;
            $stmt = $pdo->prepare( $query );


            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $password = $_POST['old_password'];
            $passwordHash = $user['password'];

            if (!password_verify($password, $passwordHash)) {

                throw new Exception("Wrong password!");

            }
            elseif($_POST['password'] !== $_POST['confirmPass']){
                throw new Exception("New password and confirm new password doesn't match!");
            }
            else{
                $query = "UPDATE users 
                    SET password=:password
                    WHERE id= ".$_GET['id'];

                $newPassword=htmlspecialchars(strip_tags($_POST['password']));
                $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':password', $newPasswordHash);
                $result = $stmt->execute();

            }

        }
        catch(Exception $exception){
            $error = $exception->getMessage();
        }



    }
    ?>

    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <form id="updateUser" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Old Password:</td>
                        <td><input type='password' name='old_password' class='form-control' required /></td>
                    </tr>
                    <tr>
                        <td>New Password:</td>
                        <td><input type='password' name='password' class='form-control'  required/></td>
                    </tr>
                    <tr>
                        <td>Confirm Password:</td>
                        <td><input type='password' name='confirmPass' class='form-control' required/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save' class='btn btn-success' />
                            <a href='profile.php' class='btn btn-danger'>Back to your profile</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-sm-2"></div>
    </div>

</div> <!-- end .container -->
<footer class="footer navbar-fixed-bottom">
    <?php include_once "php_includes/footer.php"; ?>
</footer>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
<script rel="script" type="text/javascript" src="js/validationFE.js"></script>

</body>
</html>