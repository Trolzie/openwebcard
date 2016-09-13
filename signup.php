<?php //include config
require_once('config.php');

//if not logged in redirect to login page
// if(!$user->is_logged_in()){ header('Location: login.php'); }

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Open Web Card - Signup</title>
	<!-- <link rel="stylesheet" href="../css/style.css"> -->
</head>
<body>

<div id="wrapper">

	<?php //include('menu.php');?>
	<!-- <p><a href="users.php">User Admin Index</a></p> -->

	<!-- <h2>Add User</h2> -->

	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//very basic validation
		// if($username ==''){
		// 	$error[] = 'Please enter the username.';
		// }
		if($email ==''){
			$error[] = 'Please enter the email address.';
		}

		if($password ==''){
			$error[] = 'Please enter the password.';
		}

		if($passwordConfirm ==''){
			$error[] = 'Please confirm the password.';
		}

		if($password != $passwordConfirm){
			$error[] = 'Passwords do not match.';
		}


		if(!isset($error)){

			$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);

			try {

				//insert into database
				$stmt = $db->prepare('INSERT INTO owc_users (email,password) VALUES (:email, :password)');
				$stmt->execute(array(
					':email' => $email,
					':password' => $hashedpassword
				));

				$userId = $db->lastInsertId();

				try {

					//insert into database
					$stmt = $db->prepare('INSERT INTO owc_userdata (userKey) VALUES (:userId)');
					$stmt->execute(array(
						':userId' => $userId
					));

					//send confirmation email
					$code=substr(md5(mt_rand()),0,15);
					$message = "Your Activation Code is ".$code."";
					$to=$email;
					$subject="Activation Code For OpenWebCard.com";
					// $from='no-reply@openwebcard.com';
					// $headers = "From:".$from;
					// $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
					$fromTitle = "From Title";
					$emailFrom = 'no-reply@openwebcard.com';
					$emailTo   = $email;
					$header = "From: $fromTitle <$emailFrom>\r\n";
					$header .= "Reply-To: ".$emailFrom."\r\n";
					$header .= "MIME-Version: 1.0\r\n";
					$header .= "Content-Type: multipart/mixed; boundary=\"".$random_hash."\"\r\n\r\n";
					$header .= "This is token email.\r\n";
					$header .= "--".$random_hash."\r\n";
					$header .= "Content-type:text/html; charset=UTF-8\r\n";
					$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
					
					$body='Your Activation Code is '.$code.' Please Click On This link <a href="verification.php">Verify.php?id='.$db_id.'&code='.$code.'</a>to activate  your account.';

					mail($to,$subject,$body,$headers);

					//redirect to index page
					header('Location: signup.php?action=success');
					exit;

				} catch(PDOException $e) {
					echo $e->getMessage();
				}




				//redirect to index page
				// header('Location: ');
				exit;

			} catch(PDOException $e) {
				echo $e->getMessage();
			}

		}

	}

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>

	<?php if(isset($_GET['action'])){ ?>

		<p>A confirmation email has been sent to your inbox.</p>
		<p>go to <a href="login.php">Login</a> page.</p>

	<?php } else { ?>

		<form action='' method='post'>

			<!-- <p><label>Username</label><br />
			<input type='text' name='username' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p> -->
			<p><label>Email</label><br />
			<input type='text' name='email' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p>

			<p><label>Password</label><br />
			<input type='password' name='password' value='<?php if(isset($error)){ echo $_POST['password'];}?>'></p>

			<p><label>Confirm Password</label><br />
			<input type='password' name='passwordConfirm' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>

			<p><input type='submit' name='submit' value='Signup'></p>

		</form>

	<?php } ?>

</div>
