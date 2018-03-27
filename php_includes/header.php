<?php
require_once 'connect.php'; ?>

<h1 style="width:50%; margin:0 auto;">Quality House Beer</h1>
<nav>
    <a href="index.php"><img src="images/logoNew_bubbles.png"></a>
    <ul>
        <?php if (!isset($_SESSION['id'])): ?>
        <li><a id="home" href="index.php"><i class="fa fa-home"></i> HOME</a></li>
        <li><a id="catalog" href="catalog.php"><i class="fa fa-list"></i> CATALOG</a></li>
        <li><a id="about" href="about.php"><i class="fa fa-pencil"></i> ABOUT</a></li>
        <li><a id="register" href="register.php"><i class="fa fa-user"></i> REGISTER</a></li>
        <li><a id="login" href="login.php"><i class="fa fa-sign-in"></i> LOGIN</a></li>
        <li><a id="basket" href="basket.php"><i class="fa fa-beer"></i> BASKET</a></li>
        <li><a id="faq" href="faq.php"><i class="fa fa-question"></i> FAQ</a></li>
        <li><a href="#"><i class="fa fa-search"></i> <input
                        style="box-sizing: border-box; border: 2px solid; border-radius: 15px; height: 40px;"
                        type="text" placeholder=" Search..." name="search"></a></li>
        <?php elseif (isset($_SESSION['id']) != '1'): ?>
            <li><a id="home" href="index.php"><i class="fa fa-home"></i> HOME</a></li>
            <li><a id="catalog" href="catalog.php"><i class="fa fa-list"></i> CATALOG</a></li>
            <li><a id="about" href="about.php"><i class="fa fa-pencil"></i> ABOUT</a></li>
            <li><a id="addBeer" href="addBeer.php"><i class="fa fa-beer"></i> Add Beer</a></li>
            <li><a id="faq" href="faq.php"><i class="fa fa-question"></i> FAQ</a></li>
            <li><a id="logout" href="logout.php"><i class="fa fa-sign-out"></i> LOGOUT</a></li>
            <li><a href="#"><i class="fa fa-search"></i> <input
                            style="box-sizing: border-box; border: 2px solid; border-radius: 15px; height: 40px;"
                            type="text" placeholder=" Search..." name="search"></a></li>
        <?php else :; ?>

            <li><a id="home" href="index.php"><i class="fa fa-home"></i> HOME</a></li>
            <li><a id="catalog" href="catalog.php"><i class="fa fa-list"></i> CATALOG</a></li>
            <li><a id="about" href="about.php"><i class="fa fa-pencil"></i> ABOUT</a></li>
            <li><a id="basket" href="basket.php"><i class="fa fa-beer"></i> BASKET</a></li>
            <li><a id="faq" href="faq.php"><i class="fa fa-question"></i> FAQ</a></li>
            <li><a id="profile" href="profile.php"><i class="fa fa-user"></i> PROFILE</a></li>
            <li><a id="logout" href="logout.php"><i class="fa fa-sign-out"></i> LOGOUT</a></li>
            <li><a href="#"><i class="fa fa-search"></i> <input
                            style="box-sizing: border-box; border: 2px solid; border-radius: 15px; height: 40px;"
                            type="text" placeholder=" Search..." name="search"></a></li>
        <?php endif; ?>
    </ul>
</nav>