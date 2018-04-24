<?php
require_once __DIR__."/connect.php";

//$_GET['txt'] = 'dog';
//
//var_dump($_GET['txt']);

if(isset($_GET['txt'])){
//    var_dump($_GET['txt']);
    $text = strip_tags(htmlspecialchars($_GET['txt']));

    $getName = $pdo->prepare("SELECT * FROM products WHERE name LIKE concat('%', :name, '%') ");

    $getName->execute(array('name' => $text));

    while ($names = $getName->fetch(PDO:: FETCH_ASSOC)){
        extract($names);
        echo "<div class='product justify-content-md-center'>";
        echo "<img class='beerPicture' src='beers/{$picture}' style='height:50%; '>";
        echo "<h2 class='header my-3 text-truncate'>{$name}</h2>";
        echo "<p class='description' style='display: none;'>{$description}</p>";
        echo "<p class='price'>{$price} lv.</p>";
        if ($quantity == 0) {
            echo "<h2 class='text_shadow'>Out of Stock</h2>";
        } else {
            //echo "<div class='btn'>Add to cart</div>";
            $cartId = $names["id"];
            echo "<a class='btn' href='cartAction.php?action=addToCart&id=$cartId'>Add to cart</a>";

        }
        echo "<div class='quickview'>Description</div>";
        echo "</div>";
    };
    echo "<div class='quickviewContainer' style='margin-top: 50px;'>";
    echo "<div class='close'></div>";
    echo "<h2 class='headline'>{$name}</h2>";
    echo "<img class='picture' src='beers/{$picture}' style='height:30%;'>" ;
    echo "<p class='price my-3'>{$price}</p>";
    echo "<p class='description my-5 text-justify'>{$description}</p>";

    echo "</div>";

}else{
    echo 'Empty GET/POST ';
}
