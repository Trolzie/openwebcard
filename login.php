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
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

	<header class="header">
		<nav class="navigation">
			<ul class="list">
				<li class="list-item"><a href='help.php'>Help</a></li>
				<li class="list-item"><a href='login.php'>Login</a></li>
			</ul>
		</nav>
	</header>

	<section>
		
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

		<p>go to <a href="index.php">Home</a> page.</p>
		
	</section>

</body>
</html>
