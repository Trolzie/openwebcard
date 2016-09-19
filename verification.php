<?php //include config
require_once('config.php');

//if not logged in redirect to login page
// if(!$user->is_logged_in()){ header('Location: login.php'); }

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Open Web Card - Verification</title>
	<!-- <link rel="stylesheet" href="../css/style.css"> -->
</head>
<body>

<h1>Verification of Open Web Card Account.</h1>

<?php

if(isset($_GET['id']) && isset($_GET['code']))
{
	$id=$_GET['id'];
	$code=$_GET['code'];

	//find user to activate
	$stmt = $db->prepare('SELECT email FROM owc_users WHERE userID = :userID AND activated = :activated');
	$stmt->execute(array(
		':userID' => $id,
		':activated' => $code
	));
	$row = $stmt->rowCount();

	if($row==1) {

		//insert into database
		$stmt = $db->prepare('UPDATE owc_users SET activated = :activated WHERE userID = :userId');
		$stmt->execute(array(
			':activated' => NULL,
			':userId' => $id
		));

		?>

		<h2>You account has been successfuly verified, you are now able to log in.</h2>
		<p>go to <a href="login.php">login</a> page.</p>

	<?php

	} else {
		echo "something went wrong.";
		echo "Your account may already have been activated, in that case try and log in.";
		echo "If this is not the case, please contact an administrator or resend activation email.";
	}


}

?>