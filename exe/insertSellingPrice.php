<?php

include '../db_connect.php';

$houseId = $_GET['houseId'];
$sellingPrice = $_GET['selling_price'];

//Update Selling Price
$insertQuery = mysqli_query($conn , "UPDATE housedata SET sellingprice = '$sellingPrice' WHERE houseid = '$houseId'");

$fetchMockbids = mysqli_query($conn, "SELECT mockbid_id FROM mockbiddata WHERE m_house_id = '$houseId'");

//Create Notifications
while($row = mysqli_fetch_array($fetchMockbids, MYSQLI_ASSOC)){
	$mockbidId = $row[mockbid_id];
	$createNotificationQuery = mysqli_query($conn , "INSERT INTO notifications (n_mockbid_id) VALUES ('$mockbidId')");
}
?>

