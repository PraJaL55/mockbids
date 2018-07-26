<?php

include '../db_connect.php';

$houseId = $_GET['houseId'];
$sellingPrice = $_GET['selling_price'];

//Update Selling Price
$insertQuery = mysqli_query($conn , "UPDATE housedata SET sellingprice = '$sellingPrice' WHERE houseid = '$houseId'");

$fetchMockbids = mysqli_query($conn, "SELECT mockbid_id, mockbid_price FROM mockbiddata WHERE m_house_id = '$houseId'");

//Create Notifications and update score
while($row = mysqli_fetch_array($fetchMockbids, MYSQLI_ASSOC)){
	$mockbidId = $row[mockbid_id];
	$mockbidPrice = $row[mockbid_price];

	$createNotificationQuery = mysqli_query($conn , "INSERT INTO notifications (n_mockbid_id) VALUES ('$mockbidId')");

	//Calculate the score
	$individualHouseAccuracy = 100 - round((abs($sellingPrice - $mockbidPrice) / $sellingPrice) * 100, 2);

	$insertScoreQuery = mysqli_query($conn , "UPDATE mockbiddata SET mockbid_score = '$individualHouseAccuracy' WHERE mockbid_id = '$mockbidId'");
}


?>

