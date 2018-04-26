<?php

require_once 'connect.php';

if ((isset($_SESSION['user']) AND trim($_SESSION['user']) != "") OR (isset($_COOKIE['remember_me']) AND trim($_COOKIE['remember_me']) != "")) {

    if (isset($_COOKIE['remember_me']) AND trim($_COOKIE['remember_me']) != "") {

        $_SESSION['user'] = $_COOKIE['remember_me'];

    }
    header('Location: profile.php');

    exit;

}

$error = '';
try {

    if (isset($_POST['login'])) {
        $username = $_POST['username'];

        $password = $_POST['password'];       //Retrieve the field values from our registration form.

        // $username = !empty($_POST['username']) ? trim($_POST['username']) : null;

        // $password = !empty($_POST['password']) ? trim($_POST['password']) : null;//Construct the SQL statement and prepare it.

        $sql = "SELECT

            id AS id,

            username AS username,

            password AS password,

            email AS email,

            phone AS phone,

            address AS address,

            first_name AS first_name,

            last_name AS last_name,

            age AS age            

        FROM

            users

        WHERE

             username = ?

         ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $passwordHash = $user['password'];

        if (!password_verify($password, $passwordHash)) {

            setcookie('remember_me', '', time() - 100000);

            throw new Exception("Wrong username or password!");

        } else {

            if (isset($_POST['remember'])) {

                $month = time() + ((3600 * 24) * 30);

                setcookie('remember_me', $_POST['username'], $month);

            } else {

                $past = time() - 100;

                setcookie('remember_me', '', $past);

            }
            $hour = time() + 3600;

            setcookie('ID_my_site', $_POST['username'], $hour);
            $_SESSION['id'] = $user['id'];

            $_SESSION['user'] = $user['username'];
            if($_SESSION['user'] == 'admin'){
                header('Location: adminProfile.php');
                exit();
            }
            else {
                header('Location: catalog.php');
                exit();
            }

        }
    }

} catch (Exception $exception) {

    $error = $exception->getMessage();

} ?>


<!doctype html>
<html lang="en">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>

    <title>Login</title>

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
<header>
    <?php include_once "php_includes/header.php"; ?>
</header>
<div class="container">
    <article style="position: relative; margin-top: 250px">
        <div class="row justify-content-md-center">
            <div class="col-sm-3">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="alert alert-success">
                        <strong>Registration success.</strong> Please Login and buy some beer. :)
                    </div>

                <?php endif; ?>
                <?php unset($_SESSION['username']); ?>

                <?php if ($error) : ?>
                    <div class="alert alert-danger">
                        <strong> <?= $error ?></strong>
                    </div>

                <?php endif; ?>
                <?php $error = ''; ?>

                <form id="loginForm" action="#" method="post" novalidate="novalidate">
                    <fieldset>
                        <legend class="extraPlace"> Please sign in</legend>

                        <div class="input-group margin">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input type="text" id="inputEmail" name="username" class="form-control" placeholder="Username" maxlength="40" required autofocus>
                        </div>

                        <div class="input-group margin">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        </div>

                        <div class="checkbox alignLeftContent">
                            <label>
                                <input type="checkbox" value="remember-me" name="remember"> Remember me
                            </label>
                        </div>
                        <input class="btn btn-md btn-success btn-block" type="submit" name="login" value="Sign in">
                    </fieldset>
                </form>
            </div>
        </div>
    </article>
</div>
<footer class="container fixed-bottom">
    <?php include_once "php_includes/footer.php"; ?>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
<!--<script rel="script" type="text/javascript" src="js/validationFE.js"></script>-->
<script rel="script" type="text/javascript" src="js/JSValidationLogin.js"></script>
</body>
</html>
