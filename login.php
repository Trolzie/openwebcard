<?php
//include config
require_once('config.php');

//check if already logged in
if( $user->is_logged_in() ){ header('Location: profile'); }
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

		$email = trim($_POST['email']);
		$password = trim($_POST['password']);

		if($user->login($email,$password)){

			//logged in rgo to profile page
			header('Location: profile');
			exit;

		} else {
			$message = '<p class="error">Wrong email or password</p>';
		}

	}//end of submit

	if(isset($message)){ echo $message; }
	?>

	<form action="" method="post">
		<label>Email</label><input type="text" name="email" value=""  /><br>
		<label>Password</label><input type="password" name="password" value=""  /><br>
		<label></label><input type="submit" name="submit" value="Login"  /><br>
	</form>

</body>
</html>
