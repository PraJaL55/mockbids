<?php

include '../db_connect.php';

$houseId = $_GET['houseId'];
$mockbidPrice = $_GET['mockbid_price'];
$userName = $_GET['username'];

//Update mockbidPrice
$userIdQuery=mysqli_query($conn,"SELECT user_id FROM users WHERE user_name='$userName'");
while($userIdrow = mysqli_fetch_array($userIdQuery)){
	$userId = $userIdrow['user_id'];
}

$insertQuery = mysqli_query($conn , "INSERT INTO mockbiddata (m_house_id,m_user_id,mockbid_price) VALUES('$houseId','$userId','$mockbidPrice')");
?>

