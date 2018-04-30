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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" media="screen" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link href="css/styles.css" rel="stylesheet">

</head>
<body>
<header class="fixed-top">
    <?php include_once "php_includes/header.php"; ?>
</header>
<div class="container" style="margin-top: 50px; margin-bottom: 50px;">

    <div class="page-header">
        <h1>Edit Product</h1>
    </div>

    <?php
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');



    // read current record's data
    try {
        // prepare select query
        $query = "SELECT id, name, description, price, quantity, picture FROM products WHERE id = ? LIMIT 0,1";
        $stmt = $pdo->prepare( $query );

        // this is the first question mark
        $stmt->bindParam(1, $id);

        // execute our query
        $stmt->execute();

        // store retrieved row to a variable
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // values to fill up our form
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $picture = htmlspecialchars($row['picture'], ENT_QUOTES);
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

            // write update query
            // in this case, it seemed like we have so many fields to pass and
            // it is better to label them and not use question marks
            $query = "UPDATE products 
                    SET name=:name, description=:description, price=:price, quantity=:quantity
                    WHERE id=:id";


            // posted values
            $name=htmlspecialchars(strip_tags($_POST['name']));
            $description=htmlspecialchars(strip_tags($_POST['description']));
            $price=htmlspecialchars(strip_tags($_POST['price']));
            $quantity=htmlspecialchars(strip_tags($_POST['quantity']));

            if($_FILES['picture']['size'] > 0){
                $query = "UPDATE products 
                    SET picture=:picture, name=:name, description=:description, price=:price, quantity=:quantity
                    WHERE id=:id";

                $picture=!empty($_FILES["picture"]["name"])
                    ? sha1_file($_FILES["picture"]["tmp_name"]) . "-" . basename($_FILES["picture"]["name"])
                    : "";
                $picture=htmlspecialchars(strip_tags($picture));
                $stmt = $pdo->prepare($query);

                $stmt->bindParam(':picture', $picture);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->bindParam(':id', $id);

                $target_directory = "beers/";
                $target_file = $target_directory . $picture;
                $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                $file_upload_error_messages = "";

                $allowed_file_types = array("jpg", "jpeg", "png");
                if (!in_array($file_type, $allowed_file_types)) {
                    $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG files are allowed.</div>";
                }

                if (file_exists($target_file)) {
                    $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
                }


                if (($_FILES['picture']['size'] >= (5242880)) || ($_FILES["picture"]["size"] == 0)) {
                    $file_upload_error_messages .= "<div>Image must be less than 5 MB in size.</div>";
                }


                if (!is_dir($target_directory)) {
                    mkdir($target_directory, 0777, true);
                }

                if (empty($file_upload_error_messages)) {
                    if(move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)){
                        if($stmt->execute()){
                            echo "<div class='alert alert-success'>Record was updated.</div>";
                        }
                        else{
                            echo "<div class='alert alert-danger'>Unable to update record.</div>";
                        }
                    }
                    else {
                        echo "<div class='alert alert-danger'>";
                        echo "<div>Unable to upload photo.</div>";
                        echo "<div>Update the record to upload photo.</div>";
                        echo "</div>";
                    }
                }
                 else {
                    // it means there are some errors, so show them to user
                    echo "<div class='alert alert-danger'>";
                    echo "<div>{$file_upload_error_messages}</div>";
                    echo "<div>Update the record to upload photo.</div>";
                    echo "</div>";
                }
            }
            else{
                $stmt = $pdo->prepare($query);
                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->bindParam(':id', $id);


                // Execute the query
                if($stmt->execute()){
                    echo "<div class='alert alert-success'>Record was updated.</div>";

                }else{
                    echo "<div class='alert alert-danger'>Unable to update record.</div>";
                }
            }



        }

            // show errors
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }
    ?>

    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post" enctype="multipart/form-data">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' required /></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><textarea name='description' class='form-control' style="text-align: justify;" maxlength=1000 required><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
            </tr>
            <tr> 
                <td>Price</td>
                <td><input type='number' min=0.01 step="0.01" min=0 name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' required/></td>
            </tr>
            <tr>
                <td>Quantity</td>
                <td><input type='number' min=0 name='quantity' value="<?php echo htmlspecialchars($quantity, ENT_QUOTES); ?>" class='form-control'  required/></td>
            </tr>
            <tr>
                <td>Picture</td>
                <td><p><?php echo $picture ? "<img src='beers/{$picture}' style='width:150px; height:150px;' />" : "<img src='images/birichka.jpg' style='width:150px;; height:150px;';>" ?></p></td>
            </tr>
            <tr>
                <td>Change Picture</td>
                <td><input class="btn btn-default" type="file" name="picture"/></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save' class='btn btn-success' />
                    <a href='listAllBeers.php' class='btn btn-danger'>Back to read products</a>
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

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>