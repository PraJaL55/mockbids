<!DOCTYPE html>
<?php
	include 'db_connect.php';
	session_start();
	
	if(isset($_REQUEST['user_name']) && !empty($_REQUEST['user_name']) && isset($_REQUEST['password']) && !empty($_REQUEST['password'])){
		
		$username = $_REQUEST['user_name'];
		$pwd = $_REQUEST['password'];
		
		//Attempty Login
		$checkLogin  = mysqli_query($conn, "SELECT user_id FROM users WHERE user_name = '$username' AND user_pwd = '$pwd'");
		
		if(mysqli_num_rows($checkLogin) == 1){
			$_SESSION['username'] = $_REQUEST['user_name'];
			$_SESSION['loggedin'] = 1;
		}
		else{
			header('Location:index.php?loggedin=Invalid%20username%20or%20password');
		}
	}
	else {
		if(!isset($_SESSION['loggedin'])){
			header('Location:index.php?loggedin=Please%20Login%20To%20See%20The%20Page');
		}
	}

	$username = $_SESSION['username'];

	//Get unread notifications
	$getUnreadNotificationQuery = mysqli_query($conn, "SELECT notification_id, houseid, housename, sellingprice, mockbid_price, mockbid_score, read_bool  FROM notifications INNER JOIN mockbiddata ON notifications.n_mockbid_id = mockbiddata.mockbid_id INNER JOIN housedata ON housedata.houseid = mockbiddata.m_house_id INNER JOIN users ON mockbiddata.m_user_id = users.user_id WHERE users.user_name = '$username' AND notifications.read_bool = 0");

	$unreadNotificationCount = mysqli_num_rows($getUnreadNotificationQuery);

	//Get read notifications
	$getReadNotificationQuery = mysqli_query($conn, "SELECT houseid, housename, sellingprice, mockbid_price, mockbid_score, read_bool  FROM notifications INNER JOIN mockbiddata ON notifications.n_mockbid_id = mockbiddata.mockbid_id INNER JOIN housedata ON housedata.houseid = mockbiddata.m_house_id INNER JOIN users ON mockbiddata.m_user_id = users.user_id WHERE users.user_name = '$username' AND notifications.read_bool = 1");

?>


<html>
<head>
	<title>Zillow Softbids</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head>

<body>
	<?php
	//Find whether user is admin or not
	$adminQuery = mysqli_query($conn, "SELECT user_admin FROM users WHERE user_name = '$username'");
	while($row = mysqli_fetch_array($adminQuery, MYSQLI_ASSOC)){
		$user_admin = $row['user_admin'];
	}
	?>
	<div class="header">
		<img id="logo-image" src="images/zillow_logo.png">
		<div id="inside-header" class="centered">Softbids</div>
		<div class="header-btn-container">
			<a href="logout.php" class="header-btn" id="logout-btn">Logout</a>
			<a href ="mymocks.php" class="header-btn" id="mymock-btn">My bids</a>
			<?php
				if($user_admin){
			?> <a href ="admin.php" class="header-btn" id="admin-btn">Admin</a>
			<?php	} 
			?>
			<div class="outer" onclick="document.getElementById('notification-modal').style.display='block'">
				<i id="notification-icon" class="fas fa-bell"></i>
				<div class="inner" <?php if($unreadNotificationCount > 0) {?> style = "display:block" <?php } ?> >
					<i class="notification-count"><?php echo $unreadNotificationCount; ?></i>
					<i id ="circle-icon" class="fas fa-circle" ></i>
				</div>
			</div>
		</div>
	</div>
	
	<div class="container">
		<?php
		//Find all houses
		$houseQuery = mysqli_query($conn, "SELECT * FROM housedata");
		
		while ($rowmem = mysqli_fetch_array($houseQuery, MYSQLI_ASSOC)) {
			$displayPrice = $rowmem['displayprice'];
			$houseName = $rowmem['housename'];
			$sellingPrice = $rowmem['sellingprice'];
			$houseId = $rowmem['houseid'];
			$houseImage = $rowmem['houseimage'];

			$mockbidQuery = mysqli_query($conn, "SELECT mockbiddata.mockbid_price FROM users INNER JOIN mockbiddata ON users.user_id=mockbiddata.m_user_id WHERE users.user_name='$username' AND mockbiddata.m_house_id = '$houseId' ");
			while($row = mysqli_fetch_array($mockbidQuery, MYSQLI_ASSOC)){
				$mockbidPrice = $row['mockbid_price'];
			}
		?>
		<div class="card">
		  <div class="house-image" style= "background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url('<?php echo $houseImage; ?>');"></div>
		  <div class="card_container">
			<h2><b><?php echo $houseName; ?></b></h2>
			<div class="house-info">
				<h5 class = "house-info-p">Market Price: $<?php echo $displayPrice; ?> </h5>
				<h5 id="mockbid-p-<?php echo $houseId; ?>" class = "house-info-p">
				<?php if($mockbidPrice != null){ ?>
				Your Softbid Price: $<?php echo $mockbidPrice; ?>
				<?php } else { ?>
					<button class="mockbid-btn" id="mockbid-btn-<?php echo $houseId; ?>" onclick="document.getElementById('mockbid-modal-<?php echo $houseId; ?>').style.display='block'">SoftBid this house</button>
				<?php }?>
				</h5>
				<div id="clear"></div>
			 </div>
		  </div>
		</div>
		
		<!-- Softbid House Modal Modal -->
		<div id="mockbid-modal-<?php echo $houseId; ?>" class="w3-modal w3-animate-zoom">
		  <div class="w3-modal-content">
			<div class="w3-container">
				<span onclick="document.getElementById('mockbid-modal-<?php echo $houseId; ?>').style.display='none'" 
				class="w3-button w3-display-topright">&times;</span>
				<p class="mockbid-txt">Enter Your SoftBid Price</p>
				<div class="center">
					<input class="mockbid-input" type="text" id = "mockbid-price-<?php echo $houseId; ?>"></input>
				</div>
				<div class="center">
					<button class="mockbid-submit-btn" id="mockbid-submit-btn-<?php echo $houseId; ?>" onclick="enterMockbidCall('<?php echo $username; ?>',<?php echo $houseId; ?>)">Submit</button>
				</div>
			</div>
		  </div>
		</div>
		<?php $mockbidPrice = ""; } ?>


		<!-- The Notifications Modal -->
		<div id="notification-modal" class="w3-modal">
		  <div class="w3-modal-content w3-animate-zoom">
		  	<header class="w3-container w3-teal"> 
		     	<span onclick="document.getElementById('notification-modal').style.display='none'" 
		      	class="w3-button w3-display-topright">&times;</span>
		      	<h2>Notifications</h2>
		    </header>
		    <div class="w3-container">
		      	<table class="table" style="border-collapse: unset">

		      	<?php 
				//Unread Notifications
				while($row = mysqli_fetch_array($getUnreadNotificationQuery, MYSQLI_ASSOC)){
					$notificationId = $row['notification_id'];
					$houseId = $row['houseid'];
					$houseName = $row['housename'];
					$sellingPrice = $row['sellingprice'];
					$mockbidPrice = $row['mockbid_price'];
					$mockbidScore = $row['mockbid_score'];
					$read_bool = $row['read_bool'];
					?>
					<tr class="td">
						<td><i id ="circle-icon" class="fas fa-circle" ></i></td>
						<td><p> Your softbidded property - <i><?php echo $houseName; ?></i> is sold at <i>$<?php echo $sellingPrice; ?></i>&nbsp; and your Softbid Score is: <i><?php echo $mockbidScore; ?></i></p></td>
						<td><a class="more-detail" href="mymocks.php?houseid=<?php echo $houseId;?>&notificationId=<?php echo $notificationId; ?>">More details</a></td>
					</tr>
					
				<?php } ?>

				<?php
				//Read Notifications
				while($row = mysqli_fetch_array($getReadNotificationQuery, MYSQLI_ASSOC)){
					$houseId = $row['houseid'];
					$houseName = $row['housename'];
					$sellingPrice = $row['sellingprice'];
					$mockbidPrice = $row['mockbid_price'];
					$mockbidScore = $row['mockbid_score'];
					$read_bool = $row['read_bool'];
					?>
					<tr class="td">
						<td><i id ="circle-icon" class="fas fa-circle" style="color: black" ></i></td>
						<td><p> Your softbidded property - <i><?php echo $houseName; ?></i> is sold at <i>$<?php echo $sellingPrice; ?></i>&nbsp; and your Softbid Score is: <i><?php echo $mockbidScore; ?></i></p></td>
						<td><a class="more-detail" href="mymocks.php?houseid=<?php echo $houseId;?>">More details</a></td>
					</tr>
					
				<?php } ?>
				</table>
		    </div>
		  </div>
		</div>
		
		<!-- <div class="footer">
				<p style="margin:10px;">Made with <i class="fa fa-heart"> by Saloni and Prajal.</i></p>
			</div> -->
	</div> 
	<script type="text/javascript">
		function enterMockbidCall(username, houseId){
			var mockbidPrice = document.getElementById('mockbid-price-'+houseId).value;
			console.log(username);
			enterMockbid(username, houseId, mockbidPrice);
		}
	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	crossorigin="anonymous"></script>
	<script src="js/home.js"></script>
</body>
</html>