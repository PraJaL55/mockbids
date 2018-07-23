<!DOCTYPE html>
<?php
	session_start();
	if(isset($_SESSION['loggedin'])){
		header('Location:home.php');
	}
?>
<html>
<head>
	<title>Mockbids!</title>
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
	<link rel="stylesheet" href="css/main.css">
</head>
<body class="bg">
	
	<div class="centered">Mockbids</div>
	
	<div id="login_div" class="main-div">
		<form name="login_form" action = "home.php" method="post">
			<input name="user_name" type="text" placeholder="Username" id="username_field" required />
			<input name="password" type="password" placeholder="Password" id="password_field" required/>
			<button type="submit" style="width: 100%" class="bg-large">Login</button>
		</form>
	</div>
	
</body>
</html>