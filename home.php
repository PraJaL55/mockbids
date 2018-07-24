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
?>


<html>
<head>
	<title>Zillow Softbids</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head>

<body>
	<div class="header">
		<img id="logo-image" src="images/zillow_logo.png">
		<div id="inside-header" class="centered">Softbids</div>
		<div class="header-btn-container">
			<a href="logout.php" class="header-btn" id="logout-btn">Logout</a>
			<a href ="mymocks.php" class="header-btn" id="mymock-btn" onclick="">My mocks</a>
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

			$mockbidQuery = mysqli_query($conn, "SELECT mockbiddata.mockbid_price FROM users INNER JOIN mockbiddata ON users.user_id=mockbiddata.m_user_id WHERE users.user_name='$username' AND mockbiddata.m_house_id = '$houseId' ");
			while($row = mysqli_fetch_array($mockbidQuery, MYSQLI_ASSOC)){
				$mockbidPrice = $row['mockbid_price'];
			}
		?>
		<div class="card">
		  <img src="img_avatar.png" alt="Avatar" style="width:100%">
		  <div class="card_container">
			<h4><b><?php echo $houseName; ?></b></h4>
			<div class="house-info">
				<p class = "house-info-p">Market Price: $<?php echo $displayPrice; ?> </p>
				<p id="mockbid-p-<?php echo $houseId; ?>" class = "house-info-p">
				<?php if($mockbidPrice != null){ ?>
				Your Mockbid Price: $<?php echo $mockbidPrice; ?>
				<?php } else { ?>
					<button class="mockbid-btn" id="mockbid-btn-<?php echo $houseId; ?>" onclick="document.getElementById('mockbid-modal-<?php echo $houseId; ?>').style.display='block'">Mockbid this house</button>
				<?php }?>
				</p>
				<div id="clear"></div>
			 </div>
		  </div>
		</div>
		
		<!-- The Modal -->
		<div id="mockbid-modal-<?php echo $houseId; ?>" class="w3-modal w3-animate-zoom">
		  <div class="w3-modal-content">
			<div class="w3-container">
				<span onclick="document.getElementById('mockbid-modal-<?php echo $houseId; ?>').style.display='none'" 
				class="w3-button w3-display-topright">&times;</span>
				<p class="mockbid-txt">Enter Your Mockbid Price</p>
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