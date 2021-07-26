<html lang="de">
<head>
<title>Leon´s erste Website</title>
<base href="<?= $baseUrl?>">
<meta charset="utf-8">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<?php include __DIR__.'/navbar.php'?>
<header class="jumbotron">
<div class="container">
<h1>Willkommen in meinem Online-Shop</h1>
</div>
</header>
<section class="container" id="cartItems">
<div class="row">
<h2>Warenkorb</h2>
</div>
<div class="row cartItemHeader">
<div class="col-12 text-right">
Preis
</div>
</div>
<?php foreach($cartItems as $cartItem):?>
<div class="row cartItem"> 
<?php include __DIR__.'/cartItem.php';?>
</div>
<?php endforeach;?>
<div class="row">
<div class="col-12 text-right">
Summe (<?= $countCartItems ?> Artikel): <span class="price"><?= number_format($cartSum/100,2,","," ")?> €</div>
<div class="row">
<a href="index.php/checkout" class="btn btn-primary col-12">Zur Kasse gehen</a>
</div>
</section>
<script src="assets/js/bootstrap.bundle.js"></script>
</body>
</html>