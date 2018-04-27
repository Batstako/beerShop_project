<?php
require_once 'connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['user'];
$id = $_SESSION['id'];

$sql = "SELECT
            username,
            first_name,
            age,
            picture,
            info
        FROM
            users
        WHERE
             id = ?

         ";

$stmt = $pdo->prepare($sql);

$stmt->execute([$id]);

$user = $stmt->fetch();

$error = "";

if (isset($_POST['submit_info'])) {
    $query = "UPDATE users
              SET info=:infoAboutMe
              WHERE id = '$id'";

    $stmt = $pdo->prepare($query);
    
    $infoAboutMe = $_POST['infoAboutMe'];

    $stmt->bindParam(':infoAboutMe', $infoAboutMe);
    $stmt->execute([$infoAboutMe]);
    header("Location: profile.php");
    exit;
}

if (isset($_POST['submit'])) {
    $query = "UPDATE users
            SET picture=:picture
            WHERE id = '$id'";

    $stmt = $pdo->prepare($query);

    $picture = !empty($_FILES["picture"]["name"])
        ? sha1_file($_FILES["picture"]["tmp_name"]) . "-" . basename($_FILES["picture"]["name"])
        : "";
    $picture = htmlspecialchars(strip_tags($picture));
    $postSize = (int)$_SERVER['CONTENT_LENGTH'];

    $stmt->bindParam(':picture', $picture);
    try {
    if ($picture) {

            // sha1_file() function is used to make a unique file name
            $target_directory = "uploads/";
            $target_file = $target_directory . $picture;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);


        // error message is empty
            //$file_upload_error_messages = "";

            $check = getimagesize($_FILES["picture"]["tmp_name"]);
            if ($check !== false) {

            } else {
                throw new Exception("Submitted file is not an image.");
                //$file_upload_error_messages .= "<div>Submitted file is not an image.</div>";
            }
            if ($_FILES['picture']['size'] == 0) {
                throw new Exception("Please submit a file.");
                //$file_upload_error_messages .= "<div>Please submit a file.</div>";
            }


            $allowed_file_types = array("jpg", "jpeg", "png");
            if (!in_array($file_type, $allowed_file_types)) {
                throw new Exception("Only JPG, JPEG, PNG files are allowed.");
                //$file_upload_error_messages .= "<div>Only JPG, JPEG, PNG files are allowed.</div>";
            }

            if (file_exists($target_file)) {
                throw new Exception("Image already exists. Try to change file name.");
                //$file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
            }

            if ($_FILES['picture']['size'] > (5242880)) {
                throw new Exception("Image must be less than 2 MB in size.");
                //$file_upload_error_messages .= "<div>Image must be less than 2 MB in size.</div>";
            }

            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }

            if (empty($file_upload_error_messages)) {
                if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                    // TO DO: Unlink the old picture.

                    $stmt->execute();
                    header("Location: profile.php");
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo "<div>Unable to upload photo.</div>";
                    echo "<div>Update the record to upload photo.</div>";
                    echo "</div>";
                }
            } // if $file_upload_error_messages is NOT empty
            else {
                // it means there are some errors, so show them to user
                echo "<div class='alert alert-danger'>";
                echo "<div>{$file_upload_error_messages}</div>";
                echo "<div>Update the record to upload photo.</div>";
                echo "</div>";
            }
        }
        else{
            throw new Exception("Please select a picture.");
        }


        //header("Location: profile.php");
    }
    catch(Exception $exception){
        $avatarQuery = "SELECT id, username, picture FROM users WHERE id = '$id'";
        $avatarStmt = $pdo->prepare($avatarQuery);

        //$stmt->bindParam(1, $id);

        $avatarStmt->execute();

        // store retrieved row to a variable
        $row = $avatarStmt->fetch(PDO::FETCH_ASSOC);
        $avatar = htmlspecialchars($row['picture'], ENT_QUOTES);
        $error = $exception->getMessage();
    }

} else {

    $avatarQuery = "SELECT id, username, picture FROM users WHERE id = '$id'";
    $avatarStmt = $pdo->prepare($avatarQuery);

    //$stmt->bindParam(1, $id);

    $avatarStmt->execute();

    // store retrieved row to a variable
    $row = $avatarStmt->fetch(PDO::FETCH_ASSOC);
    $avatar = htmlspecialchars($row['picture'], ENT_QUOTES);

}

?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>

    <title>User Profile</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" media="screen"
          href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link href="css/styles.css" rel="stylesheet">

    <!-- remove this if you use Modernizr -->
    <script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>

</head>


<body class="text-center" style="background-color:#eee">

<header class="fixed-top">
    <?php include_once "php_includes/header.php"; ?>
</header>

<div class="container" style="margin-top: 100px; margin-bottom: 150px;">
    <div class="col-sm-1"></div>
    <div class="col-sm-10" style="text-align:center; margin-top: 50px">
        <?php if ($error) : ?>
            <div class="alert alert-danger">
                <strong> <?= $error ?></strong>
            </div>

        <?php endif; ?>
        <?php $error = ''; ?>
        <div class="row">
            <div id="avatarDiv" class="col-lg-12">
                <h3 class="font-weight-bold">Profile</h3>
                <p><?php echo $avatar ? "<img src='uploads/{$avatar}' style='max-height: 200px;
    overflow: auto;' />" : "<img src='images/avatar.jpg' style='width:300px; height:25%;';>" ?></p>
            </div>
        </div>
        <div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                  enctype="multipart/form-data">
                <div class="row justify-content-md-center mb-1">
                    <div class="col-md-auto">
                        <input class="inputfile inputfile-1" type="file" name="picture" id="image"  data-multiple-caption="{count} files selected" multiple />
                        <label for="image"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg><span>Choose a file&hellip;</span></label>
                    </div>
                </div>
                <input class="btn btn-success mb-1" type="submit" value="Upload Image" name="submit">
            </form>

            
                <div class="border rounded my-5 pt-4 pb-3 well well-lg shadow_strokes_big" style="width: 100%">
                    <div class="row justify-content-md-center">
                        <p class="col-lg-6 text-center px-2"> User name: <?= $user['username'] ?></p>
                    </div>
                    <div class="row justify-content-md-center">
                        <p class="col-lg-6 text-center px-2"> First name: <?= $user['first_name'] ?> </p>
                    </div>
                    <div class="row justify-content-md-center">
                        <p class="col-lg-6 text-center px-2"> Age: <?= $user['age'] ?></p>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-lg-6 text-center px-2"> About me: </div>
                    </div>
                    <div class="row justify-content-md-center" style="overflow: auto; text-align: justify;"> <?= $user['info'] ?> </div>
                </div>

            <form action="#" method="post">
                
            <div class="well well-lg shadow_strokes_big">
                <div class="form-group">
                    <label> Info about me </label>
                    <textarea id="infoAboutMe" name="infoAboutMe" class="form-control shadow_strokes" rows="3" maxlength="500" style="resize: none; text-align: center"></textarea>
                </div>

                <div>
                    <input id="submitInfo" class="btn btn-warning" type="submit" name="submit_info" value="Save info">
                </div>
            </form>

                <div>
                    <h3 class="welcome mt-5 font-weight-bold">My Favorite Beers</h3>
                </div>
                <div class="row justify-content-md-center my-3 py-3">
                    <?php
                        $beersSql = "SELECT b.user_id, a.username, a.id, b.product_name, b.product_id, b.product_description, b.order_qty, b.picture
                                    FROM users a 
                                    JOIN (SELECT username, u.id AS 'user_id', p.name AS 'product_name', p.description AS 'product_description', p.picture, p.id AS 'product_id', u.last_name, u.first_name, SUM(od.quantity) 
                                    AS 'order_qty' FROM products p 
                                    LEFT JOIN order_detail od ON p.id=od.product_id 
                                    LEFT JOIN orders o ON od.order_id=o.id 
                                    LEFT JOIN users u ON o.user_id=u.id WHERE product_id IS NOT NULL
                                     GROUP BY u.username, p.id 
                                     ORDER BY u.username, order_qty DESC) AS b ON a.id=b.user_id WHERE a.id = '$id' LIMIT 3";
                        $beersStmt = $pdo->prepare($beersSql);
                        $beersStmt->execute();

                        while ($row = $beersStmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<div class='polaroid rounded col-sm-3 mx-5 shadow_strokes'>";
                            echo "<div class='col-md py-4'>";
                            echo "<img style='width: 160px;' src='beers/{$picture}'>";
                            echo "<p> {$product_name} </p>";
                            echo "<p> You have ordered: {$order_qty}</p>";
                            echo "</div>";
                            echo "</div>";
                        }
                    ?>
                </div>                    
            </div>
            <?php
            echo "<div class='justify-content-md-center row my-3'>
                <a href='changeProfile.php' class='btn btn-warning mx-3 col-lg-2'>Change info</a>
                <a href='#' class='btn btn-warning mx-3 col-lg-2'>My orders</a>
            </div>
            <div class='justify-content-md-center row my-3'>
                <a href='wallet.php' class='btn btn-warning mx-3 col-lg-2'>My wallet</a>
                <a href='viewCart.php' class='btn btn-warning mx-3 col-lg-2'>Basket</a>
            </div>";
            ?>
        </div>

        <div class="col-sm-1"></div>
    </div>


    <footer class="container fixed-bottom">

        <?php include_once "php_includes/footer.php"; ?>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {
            $("#profile").addClass('text_shadow');

        });
    </script>    
    <script src="js/upload-avatar-button.js"></script>
</body>

</html>