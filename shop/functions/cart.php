<?php
function addProductToCart(int $userId, int $productId, int $quantity = 1){
$sql ="INSERT INTO cart 
SET quantity=:quantity,user_id = :userId,product_id = :productId
ON DUPLICATE KEY UPDATE quantity = quantity +1";
$statement = getDB()->prepare($sql);

$statement->execute([
':userId'=> $userId,
':productId'=> $productId
]);
}

function countProductsInCart(int $userId){
$sql ="SELECT COUNT(id) FROM cart WHERE user_id =".$userId; 
$cartResults = getDb()->query($sql);
if($cartResults === false){
var_dump(printDBErrorMessage());
return 0;
}
$cartItems = $cartResults->fetchColumn();
return $cartItems;

}

function getCartItemsForUserId(int $userId):array{
$sql ="SELECT product_id,title,description,price,quantity
FROM cart
JOIN products ON(cart.product_id = products.id)
WHERE user_id = ".$userId;
$results = getDB()->query($sql);
if($results === false){
return [];
}
$found = [];
while($row = $results->fetch()){
$found[]=$row;
}
return $found;
}
function getCartSumForUserId(int $userId): int{
$sql ="SELECT SUM(price * quantity)
FROM cart 
JOIN products ON(cart.product_id = products.id)
WHERE user_id = ".$userId;
$results = getDB()->query($sql);
if($results === false){
return 0;
}
return (int)$results->fetchColumn();
}

function deleteProductInCartForUserId(int $userId, int $productId):int{
$sql ="DELETE FROM cart WHERE user_id = :userId AND product_id = : productId";
$statement = getDb()->prepare($sql);
if(false === $statement){
return 0;
}
return $statement->execute(
[
':userId'=>$uderId,
':productId'=>$productId
]
);
}

function moveCartProductsToAnotherUser(int $sourceUserId,int $targetUserId){
$oldCartItems = getCartItemsForUserId($sourceUserId);
if(count($soldCartItems) > 0){
return 0;
}
$movedProducts = 0;
foreach($oldCartItems as $oldCardItem){
addProductToCart($targetUserId,$oldCardItem['product_id'],(int)$oldCartItem['quantity']);
$movedProducts += deleteProductInCartForUserId($sourceUserId,(int)$oldCartItem['product_id']);
}
return $movedProducts;
}