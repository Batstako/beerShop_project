<?php

require_once 'connect.php';

if( isset($_COOKIE['remember_me']) AND trim($_COOKIE['remember_me'] ) != "" ) {

    $_SESSION['user']  = $_COOKIE['remember_me'];

}?>
<h1 style="width:50%; margin:0 auto;">Quality House Beer</h1>
<nav class="header" id="myHeader">
    <a href="index.php"><img src="images/logoNew_bubbles.png"></a>
    <ul>
        <?php if (!isset($_SESSION['user']) OR trim( $_SESSION['user'] ) == ""): ?>
            <li><a id="home" href="index.php"><i class="fa fa-home"></i> HOME</a></li>
            <li><a id="catalog" href="catalog.php"><i class="fa fa-list"></i> CATALOG</a></li>
            <li><a id="about" href="about.php"><i class="fa fa-pencil"></i> ABOUT</a></li>
            <li><a id="register" href="register.php"><i class="fa fa-user"></i> REGISTER</a></li>
            <li><a id="login" href="login.php"><i class="fa fa-sign-in"></i> LOGIN</a></li>
            <li><a id="faq" href="faq.php"><i class="fa fa-question"></i> FAQ</a></li>
            <li><i class="fa fa-search"></i>
                    <input id="search_id" style="box-sizing: border-box; border: 2px solid; border-radius: 15px; height: 40px;" type="text" placeholder=" Search..." name="search">

            </li>

        <?php elseif (isset($_SESSION['user']) && $_SESSION['user'] == 'admin'): ?>
            <li><a id="home" href="index.php"><i class="fa fa-home"></i> HOME</a></li>
            <li><a id="catalog" href="catalog.php"><i class="fa fa-list"></i> CATALOG</a></li>
            <li><a id="logout" href="logout.php"><i class="fa fa-sign-out"></i> LOGOUT</a></li>
            <li><i class="fa fa-search"></i>
                    <input id="search_id" style="box-sizing: border-box; border: 2px solid; border-radius: 15px; height: 40px;width: 220px;" type="text" placeholder=" Search..." name="search">

            </li>
            <li><a id="profile" href="adminProfile.php"><i class="fa fa-user"></i> <?= $_SESSION['user'] ?></a></li>

        <?php else :; ?>
            <li><a id="home" href="index.php"><i class="fa fa-home"></i> HOME</a></li>
            <li><a id="catalog" href="catalog.php"><i class="fa fa-list"></i> CATALOG</a></li>
            <li><a id="about" href="about.php"><i class="fa fa-pencil"></i> ABOUT</a></li>
            <li><a id="basket" href="viewCart.php"><i class="fa fa-beer"></i> BASKET</a></li>
            <li><a id="faq" href="faq.php"><i class="fa fa-question"></i> FAQ</a></li>
            <li><a id="logout" href="logout.php"><i class="fa fa-sign-out"></i> LOGOUT</a></li>
            <li><i class="fa fa-search"></i>
                    <input  id="search_id" style="box-sizing: border-box; border: 2px solid; border-radius: 15px; height: 40px;width: 300px;" type="text" placeholder=" Search..." name="search" >

            </li>
            <li><a id="profile" href="profile.php"><i class="fa fa-user"></i> <?= $_SESSION['user'] ?></a></li>
        <?php endif; ?>
    </ul>
</nav>