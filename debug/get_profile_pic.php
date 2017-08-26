<?php   
session_start(); 
require_once('../api/php-graph-sdk-5.5/src/Facebook/autoload.php'); 

$fb = new Facebook\Facebook([
  'app_id' => '1443647129088057',
  'app_secret' => '65dc6764b9d4f2d4fe5cd31cf5cd718d',
  'default_graph_version' => 'v2.10',
]);

$helper = $fb->getCanvasHelper();
$permissions = ['email']; // optionnal
$requestPicture = $fb->get('/piolo.kontes/picture?redirect=false&height=300'); //getting user picture
print $requestPicture; 

