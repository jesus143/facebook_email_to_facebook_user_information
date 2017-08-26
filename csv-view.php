<?php
session_start();
require_once('includes/FacebookEmailConverter.php');
$facebookEmailConverter = new FacebookEmailConverter();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Facebook Api Email Checker!</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js" ></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" ></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" >



    <link rel="stylesheet" type="text/css" href="<?php echo $_SERVER['HTTP_HOST']; ?>/public/css/style.css">

</head>
<body>

<div class="container" style="padding:20px;">
    <div class="alert alert-info">Saved CSV Files.</div>


    <br>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php $facebookEmailConverter->getDisplayUiCSVFiles(); ?>
        </div>
    </div>
</div>
</body>
</html>