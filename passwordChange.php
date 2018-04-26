<?php
include 'php_includes/validations.php';
require_once 'connect.php';
if (isset($_SESSION['id'])) {
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
    <title>Change Password</title>

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



    <div class="page-header">
        <h1>Change your details</h1>
    </div>


    <?php
    $id= $_SESSION['id'];

    if($_POST){

        try{
            $password = $_POST['old_password'];
            $query = "SELECT * FROM users WHERE id = " .$id;
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $oldPassword = $row['password'];

            if(!password_verify($password, $oldPassword)){
                throw new Exception("Wrong password!");
            }

            validatePasswords($_POST['password'], $_POST['confirmPass']);

            $updateQuery = "UPDATE users 
                    SET password=:password
                    WHERE id= " .$id;
            $newPassword=htmlspecialchars(strip_tags($_POST['password']));
            $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':password', $newPasswordHash);
            if($updateStmt->execute()){
                echo "<div class='alert alert-success'>Your profile has been updated.</div>";
                } else{
                    echo "<div class='alert alert-danger'>Unable to update profile.</div>";
                }

        }
        catch(Exception $exception){
            $error = $exception->getMessage();
            //var_dump($error);
        }




    }
    ?>
    <?php if ($error) : ?>
        <div class="alert alert-danger">
            <strong> <?= $error ?></strong>
        </div>

    <?php endif; ?>
    <?php $error = ''; ?>

    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <form id="updateUser" action="#" method="post">
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
