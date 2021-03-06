<?php
include 'php_includes/validations.php';
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
    <title>Edit Profile</title>

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



    // read current record's data
    try {
        // prepare select query
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $pdo->prepare( $query );

        // this is the first question mark
        $stmt->bindParam(1, $id);

        // execute our query
        $stmt->execute();

        // store retrieved row to a variable
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // values to fill up our form
        $username = $row['username'];
        $email = $row['email'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $address = $row['address'];
        $phone = $row['phone'];
        $age = $row['age'];
    }

// show error
    catch(PDOException $exception){
        die('ERROR: ' . $exception->getMessage());
    }
    ?>

    <?php

    // check if form was submitted
    if($_POST){

        try{
            // posted values
            $username=htmlspecialchars(strip_tags($_POST['username']));
            $first_name=htmlspecialchars(strip_tags($_POST['first_name']));
            $last_name=htmlspecialchars(strip_tags($_POST['last_name']));
            $email=htmlspecialchars(strip_tags($_POST['email']));
            $address=htmlspecialchars(strip_tags($_POST['address']));
            $age=htmlspecialchars(strip_tags($_POST['age']));
            $phone=htmlspecialchars(strip_tags($_POST['phone']));

            // VALIDATE
            validateUsername($username);
            validateEmail($email);
            validatePhone($phone);
            validateAge($age);

            $userQuery = "SELECT * FROM users WHERE id = " .$id;
            $userStmt = $pdo->prepare($userQuery);
            $userStmt->execute();
            $row = $userStmt->fetch(PDO::FETCH_ASSOC);

            if($row['username'] != $username){
                checkUsernameExists($username, $pdo);
            }

            if($row['email'] != $email){
                checkEmailExists($email, $pdo);
            }

            $query = "UPDATE users 
                    SET username=:username, first_name=:first_name, last_name=:last_name, email=:email,
                    address=:address, age=:age, phone=:phone
                    WHERE id=" .$id;
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':phone', $phone);


                // Execute the query
            if($stmt->execute()){
                echo "<div class='alert alert-success'>Your profile has been updated.</div>";
                }else{
                    echo "<div class='alert alert-danger'>Unable to update profile.</div>";
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
            <?php if ($error) : ?>
                <div class="alert alert-danger">
                    <strong> <?= $error ?></strong>
                </div>

            <?php endif; ?>
            <?php $error = ''; ?>
            <form id="updateUser" action="#" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Username:</td>
                        <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' required /></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input name='email' class='form-control' style="text-align: justify;" maxlength=1000 value="<?php echo htmlspecialchars($email, ENT_QUOTES);  ?>" required/></td>
                    </tr>
                    <tr>
                        <td>First name:</td>
                        <td><input type='text' name='first_name' value="<?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?>" class='form-control' required/></td>
                    </tr>
                    <tr>
                        <td>Last name:</td>
                        <td><input type='text' name='last_name' value="<?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?>" class='form-control' required/></td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td><input type='text' name='address' value="<?php echo htmlspecialchars($address, ENT_QUOTES); ?>" class='form-control'  required/></td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td><input type='number' min=0 name='phone' value="<?php echo htmlspecialchars($phone, ENT_QUOTES); ?>" class='form-control'  required/></td>
                    </tr>
                    <tr>
                        <td>Age:</td>
                        <td><input type='number' min=0 name='age' value="<?php echo htmlspecialchars($age, ENT_QUOTES); ?>" class='form-control'  required/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save' class='btn btn-success' />
                            <a href='profile.php' class='btn btn-danger'>Back to your profile</a>
                            <a href='passwordChange.php' class='btn btn-danger'>Change Password</a>
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