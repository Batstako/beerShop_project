<!doctype html>
<html lang="en">
    <head>
        <link rel="shortcut icon" href="images/logoNew_bubbles.png"/>
        <link type="text/css" rel="stylesheet" media="screen" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="css/styles.css" rel="stylesheet">

        <title>QH Beer</title>
    </head>
    
    <body style="background:url(images/beer_background-Copy2.jpg); width: 100%; min-height:100%; background-size: cover;  overflow: hidden; ">
    <header>
      <?php include_once "php_includes/header.php"; ?>
    </header>  
 

    <div class="row">
        <div class="col-lg-5"></div>
        <div class="col-lg-6">
        
            <div class="main_info">
            <h2 class="welcome">Welcome to Quality House Beer Website</h2>
            <p>We are Quality House Beer Company and we started our beer production business in 2015.
            Our mission is to provide the customer with the desired pleasure of consuming quality beer.
            That's why we started this project with enthusiasm and over the years we have made efforts to improve the taste of our beer.</p>
            </div>
            <div class="col-lg-12 beers">           
                <div class="col-lg-12 best_beers">
                    <h2 class="welcome">BEST SELLING BEERS</h2>
                    <?php
                    $query = ("SELECT * FROM (
                    SELECT * FROM products ORDER BY times_sold DESC LIMIT 5
                    ) as r ORDER BY times_sold DESC");
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $num = $stmt->rowCount();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $beerQuery = ("SELECT * FROM products WHERE id = " .$row['id']);
                        $exec = $pdo->prepare($beerQuery);
                        $exec->execute();
                        $beerRow = $exec->fetch (PDO::FETCH_ASSOC);
                        echo "<div class='fron_beer'>";
                        echo "<p><img style='height: 100px;' src='beers/{$beerRow['picture']}'></p>";
                        echo "<p>{$beerRow['name']}</p>";
                        echo "<p>Sold: {$beerRow['times_sold']}</p>";
                        echo "</div>";
                    }
                    ?>
                </div> 

                <div class="col-lg-12 last_sold">
                    <h2 class="welcome">LAST 3 SOLD BEERS</h2>
                    <?php
                    $query = ("SELECT * FROM (
                    SELECT * FROM order_detail ORDER BY id DESC LIMIT 3
                    ) as r ORDER BY id DESC");
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $num = $stmt->rowCount();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $beerQuery = ("SELECT * FROM products WHERE id = " .$row['product_id']);
                        $exec = $pdo->prepare($beerQuery);
                        $exec->execute();
                        $beerRow = $exec->fetch (PDO::FETCH_ASSOC);
                        echo "<div class='fron_beer'>";
                        echo "<p><img style='height: 100px;' src='beers/{$beerRow['picture']}'></p>";
                        echo "<p>{$beerRow['name']}</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-1"></div>
    </div>  
    
    

    <footer class="container fixed-bottom">
      <?php include_once "php_includes/footer.php"; ?>
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $("#home").addClass('text_shadow');
//            $("#search_id").on('focus', function(){
//                window.location.href = 'catalog.php';
//            })
        });
    </script>

    </body>
</html>