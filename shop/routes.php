<?php
$url = $_SERVER['REQUEST_URI'];

$indexPHPPosition = strpos($url,'index.php');
$baseUrl = $url;
if(false !== $indexPHPPosition){
$baseUrl = substr($baseUrl,0,$indexPHPPosition);
}

if(substr($baseUrl,-1) == '/'){
$baseUrl .='/';
}

$route = null;
$_SESSION['redirectTarget'] = $baseUrl.'index.php';
if(false !== $indexPHPPosition){
$route = substr($url,$indexPHPPosition);
$route = str_replace('index.php','',$route);
}

$userId = getCurrentUserId();
$countCartItems = countProductsInCart($userId);

if(!$route){
$products = getAllProducts();
require __DIR__.'/templates/Main.php';
exit();

}

if(strpos($route,'/cart/add/') !== false){
$routeParts = explode('/',$route);
$productId = (int)$routeParts[3];
addProductToCart($userId, $productId);
header("Location: " .$baseUrl."index.php");
exit();

}
if(strpos($route,'/cart') !== false){

$cartItems = getCartItemsForUserId($userId);
$cartSum = getCartSumForUserId($userId);
require __DIR__.'/templates/cartPage.php';
exit();
}
if(strpos($route,'/login')!== false){
$isPost = isPost();
$username ="";
$password ="";
$errors = [];
$hasErrors = false;
if($isPost){

$username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST,'password');

if(false === (bool)$username){
$errors[]="Benutzername ist leer";
 }
 if(false === (bool)$password){
 $errors[]="Passwort ist leer";
 }
 $userData = getUserDataForUsername($username);
 if((bool)$username && 0 === count($userData)){
 $errors[]="Benutzername existiert nicht";
 }
 if((bool)$password && 
 isset($userData['password']) &&
 false === password_verify($password,$userData['password'])
 ){
 $errors[]="Passwort stimmt nicht";
 }
 if(0 === count($errors)){
 $_SESSION['userId'] = (int)$userData['id'];
 moveCartProductsToAnotherUser($_COOKIE['userId'],(int)$userData['id']);

$userId = getCurrentUserId();
 setCookie('userId',$userId,strtotime('+30 days'),$path);
 header("Location: ". $_SESSION['redirectTarget']);
 exit();
 }
}
$hasErrors = count($errors) > 0;

require __DIR__.'/templates/login.php';
exit();
}
if(strpos($route,'/checkout')!== false){
if(!isLoggedIn()){
header("Location: " .$baseUrl."index.php/login");
exit();
}
$recipient = "";
$city = "";
$street = "";
$streetNumber = "";
$zipCode = "";
$recipientIsValid = true;
$cityIsValid = true;
$streetIsValid = true;
$streetNumberIsValid = true;
$zipCodeIsValid = true;
$errors = [];
$hasErrors = count($errors) <0;
require __DIR__.'/templates/selectDeliveryAddress.php';
exit();
}

if(strpos($route,'/logout') !== false){
session_regenerate_id(true);
session_destroy();
$redirectTarget = $baseUrl.'index.php';
if(isset($_SESSION['redirectTarget'])){
$redirectTarget = $_SESSION['redirectTarget'];
}
header("Location: ". $redirectTarget);
exit();
}

if(strpos($route,'/deliveryAddress/add') !== false){
if(!isLoggedIn()){
$_SESSION['redirectTarget'] = $baseURL.'index.php/deliverAddress/add';
header("Location: ". $baseUrl."index.php/login");
exit();
}
$recipient = "";
$city = "";
$street = "";
$streetNumber = "";
$zipCode = "";
$recipientIsValid = true;
$cityIsValid = true;
$streetIsValid = true;
$streetNumberIsValid = true;
$zipCodeIsValid = true;
$isPost = isPost();
$errors = [];
if($isPost){
$recipient = filter_input(INPUT_POST,'recipient',FILTER_SANITIZE_SPECIAL_CHARS);
$recipient = trim($recipient);
$city = filter_input(INPUT_POST,'city',FILTER_SANITIZE_SPECIAL_CHARS);
$city = trim($city);
$street = filter_input(INPUT_POST,'street',FILTER_SANITIZE_SPECIAL_CHARS);
$street = trim($street);
$streetNumber = filter_input(INPUT_POST,'streetNumber',FILTER_SANITIZE_SPECIAL_CHARS);
$streetNumber = trim($streetNumber);
$zipCode = filter_input(INPUT_POST,'zipCode',FILTER_SANITIZE_SPECIAL_CHARS);
$zipCode = trim($zipCode);

if(!$recipient){
$errors[]="Bitte Empf??nger eintragen";
$recipientIsValid = false;
}
if(!$city){
$errors[]="Bitte Stadt eintragen";
$cityIsValid = false;
}
if(!$street){
$errors[]="Bitte Stra??e eintragen";
$streetIsValid = false;
}
if(!$streetNumber){
$errors[]="Bitte Hausnummer eintragen";
$streetNumberIsValid = false;
}
if(!$zipCode){
$errors[]="Bitte PLZ eintragen";
$zipCodeIsValid = false;
}
if(count($errors) === 0){
$deliveryAddressId = saveDeliveryAddressForUser($userId,$recipient,$city,$zipCode,$street,$streetNumber);
if($deliveryAddressId > 0){
$_SESSION['deliveryAddressId'] = $deliverAddressId;
header("Location: ".$baseUrl."index.php/selectPayment");
exit();
}
$errors[]="Fehler beim Speichern der Lieferadresse";
}
}
$hasErrors = count($errors) > 0;

require __DIR__.'/templates/selectDeliveryAddress.php';
exit();
}

if(strpos($route,'/deliveryAddress/add') !== false){

exit();
}