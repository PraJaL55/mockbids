<!DOCTYPE html>
<?php
	include 'db_connect.php';
	session_start();
	if(!isset($_SESSION['loggedin'])){
			header('Location:index.php?loggedin=Please%20Login%20To%20See%20The%20Page');
		}

	$notificationId = isset($_REQUEST['notificationId']) ? $_REQUEST['notificationId'] : null;
	$notificationHouseId = isset($_REQUEST['houseid']) ? $_REQUEST['houseid'] : null;

	//Read notification
	if($notificationId != null){
		$readNotificationQuery = mysqli_query($conn, "UPDATE notifications SET read_bool = 1 WHERE notification_id = '$notificationId'");
	}
?>
<html>
<head>
	<title>My Softbids</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head>

<body>
	<div class="header">
		<img id="logo-image" src="images/zillow_logo.png">
		<div id="inside-header" class="centered">Softbids</div>
		<div class="header-btn-container">
			<a href="logout.php" class="header-btn" id="logout-btn">Logout</a>
			<a href ="home.php" class="header-btn" id="mymock-btn" onclick="">Return to Home</a>
		</div>
	</div>
	<div class="container" style="margin-top:20px">
		<?php
		$userName=$_SESSION['username'];
		$mockHouseQuery = mysqli_query($conn, "SELECT housedata.houseid, housedata.housename, housedata.displayprice, housedata.sellingprice, mockbiddata.mockbid_price, mockbiddata.mockbid_score, mockbiddata.mockbid_id FROM users INNER JOIN mockbiddata ON users.user_id=mockbiddata.m_user_id INNER JOIN housedata ON mockbiddata.m_house_id=housedata.houseid WHERE users.user_name = '$userName'");

		$totalAccuracy = 0;
		$count = 0;
		while ($rows = mysqli_fetch_array($mockHouseQuery, MYSQLI_ASSOC)) {
			$displayPrice = $rows['displayprice'];
			$houseName = $rows['housename'];
			$sellingPrice = $rows['sellingprice'];
			$houseId = $rows['houseid'];
			$mockbidPrice = $rows['mockbid_price'];
			$mockbidScore = $rows['mockbid_score'];
			$mockbidId = $rows['mockbid_id'];

			if($sellingPrice != null){
				//Calculate the error
				$individualHouseAccuracy = 100 - round((abs($sellingPrice - $mockbidPrice) / $sellingPrice) * 100, 2);
				//insert into db
				if($mockbid_score == null){
					$insertScoreQuery = mysqli_query($conn, "UPDATE mockbiddata SET mockbid_score = '$individualHouseAccuracy' WHERE mockbid_id = '$mockbidId'");
				}
				$totalAccuracy = $totalAccuracy + ($individualHouseAccuracy / 100);
				$count++;
			} else {
				$individualHouseAccuracy = 0;
			}
		?>

		<div class="list-group">
		    <a href="#" class="list-group-item">
		     	<h4 class="list-group-item-heading">House Name: <?php echo $houseName; ?> &nbsp &nbsp<?php if($individualHouseAccuracy != 0){ ?> Bid Accuracy: <?php echo $individualHouseAccuracy; ?> % <?php } else { ?> House Not Sold <?php } ?> </h4>
		     	<div>
			     	<button class="more-info" id="more-info-<?php echo $houseId; ?>" onclick="document.getElementById('mockbid-modal-<?php echo $houseId; ?>').style.display='block'">Click for details</button>

			      	<div id="mockbid-modal-<?php echo $houseId; ?>" class="w3-modal w3-animate-zoom">
				  		<div class="w3-modal-content">
							<div class="w3-container">
								<span onclick="document.getElementById('mockbid-modal-<?php echo $houseId; ?>').style.display='none'" 
								class="w3-button w3-display-topright">&times;</span>
								<p class="mockbid-txt">Softbid Details</p>
								<div class="center" style="width: 100%">
									<table class="table">
		  								<tr class="th">
		    								<th>House Name</th>
		    								<th>Your Softbid Price</th>
		    								<th>Selling Price</th>
		    								<th>Your accuracy</th>
		  								</tr>
		  								<tr class="td">
		    								<td><?php echo $houseName; ?></td>
		    								<td><?php echo $mockbidPrice; ?></td>
		    								<td><?php if($sellingPrice != 0){ echo $sellingPrice;} else { echo "-"; } ?></td>
		    								<td><?php if($individualHouseAccuracy != 0){ echo $individualHouseAccuracy;} else { echo "-"; }  ?></td>
		  								</tr>
		  							</table>
								</div>
							</div>
				  		</div>
				 	</div>
		     	</div>
		    </a>
		</div>

		<br>

		<?php } 
		$totalAccuracyScore=round($totalAccuracy/$count,4); ?>
		<div class="final-score">
			<p>Your Total ZSoftBid Score is: <br><?php echo $totalAccuracyScore*100; ?></p>
		</div>
	</div> 

	<?php if($notificationHouseId != null){ ?>
		<script type="text/javascript">
			document.getElementById('mockbid-modal-<?php echo $notificationHouseId; ?>').style.display='block';
		</script>
	<?php } ?>

	<div class="center">
		<button class="view-analytics" id="chart-btn" onclick="document.getElementById('chartContainer').style.display='block' ">View Analytics</button>
	</div>

	<?php include "includes/chart.php"; ?>

</body>
</html>
