<div class="card">
<div class = "card-title">
<?= $product['title']?>
</div>
<img src="http://placekitten.com/286/180" class="card-img-top" alt="...">
<div class="card-body">
<?= $product['description']?>
<hr>
<?= $product['price']?>
</div>
<div class="card-footer">
<a href="" class="btn btn-primary btn-sm">details</a>
<a href="index.php/cart/add/<?= $products['id']?>" class="btn btn-success btn-sm">In den Warenkorb</a>
</div>
</div>