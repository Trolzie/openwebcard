<?php
//include config
require_once('config.php');

//check if already logged in
//if( $user->is_logged_in() ){ header('Location: profile.php'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Open Web Card - Login</title>
	<!-- <link rel="stylesheet" href="../css/style.css"> -->
</head>
<body>

	<h1>Login to Open Web Card</h1>

	<?php

	//process login form if submitted
	if(isset($_POST['submit'])){

		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		if($user->login($username,$password)){

			//logged in rgo to profile page
			header('Location: profile.php');
			exit;

		} else {
			$message = '<p class="error">Wrong username or password</p>';
		}

	}//end of submit

	if(isset($message)){ echo $message; }
	?>

	<form action="" method="post">
	<p><label>Username</label><input type="text" name="username" value=""  /></p>
	<p><label>Password</label><input type="password" name="password" value=""  /></p>
	<p><label></label><input type="submit" name="submit" value="Login"  /></p>
	</form>

</body>
</html>
