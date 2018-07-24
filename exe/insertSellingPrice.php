<?php

include '../db_connect.php';

$houseId = $_GET['houseId'];
$sellingPrice = $_GET['selling_price'];

//Update Selling Price
$insertQuery = mysqli_query($conn , "UPDATE housedata SET sellingprice = '$sellingPrice' WHERE houseid = '$houseId'");
?>

