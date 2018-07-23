<?php

include '../db_connect.php';

$houseId = $_GET['houseId'];
$mockbidPrice = $_GET['mockbid_price'];

//Update mockbidPrice
$insertQuery = mysqli_query($conn , "UPDATE housedata SET mockbidprice = '$mockbidPrice' WHERE houseid = '$houseId'");
?>

