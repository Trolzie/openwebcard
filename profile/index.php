<?php
//include config
require_once('../config.php');

if ($_SESSION['loggedin'] == 1) {
	echo "logged in, " . "user id: " . $_SESSION['userID'];
} else {
	echo "logged out";
}

//if not logged in redirect to login page

// if(!$user->is_logged_in()){ header('Location: ../index.php'); }

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Profile</title> <!-- add username once established -->
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>

	<header></header>

	<section>
		
		<h1>Open Web Card Profile</h1>

		<p>welcome to your profile. Before we can create an online businesscard, we need to find out what you want to display.</p>

		<h2>Your current profile:</h2>

		<?php

			if(isset($_POST['submit'])){

				$_POST = array_map( 'stripslashes', $_POST );

				//collect form data
				extract($_POST);

				if(!isset($error)){

					try {

						//insert into database
						$stmt = $db->prepare('UPDATE owc_userdata SET userImgUrl = :userImgUrl, userHeading = :userHeading, userSubheading = :userSubheading, userBody = :userBody, userProfileTheme = :userProfileTheme WHERE userKey = :userKey');
						$stmt->execute(array(
							':userImgUrl' => $userImgUrl,
							':userHeading' => $userHeading,
							':userSubheading' => $userSubheading,
							':userBody' => $userBody,
							':userProfileTheme' => $userProfileTheme,
							':userKey' => $userKey
						));

						// redirect to index page
						// header('Location: index.php?action=updated');
						header('Location: index.php');
						exit;

					} catch(PDOException $e) {
					    echo $e->getMessage();
					}
				}
			}

		?>

		<?php
			$userID = $_SESSION['userID'];
			$stmt = $db->prepare('SELECT userImgUrl, userHeading, userSubheading, userBody, userProfileTheme, userKey FROM owc_userdata WHERE userKey = :userKey');
			$stmt->execute(array(':userKey' => $_SESSION['userID']));
			$row = $stmt->fetch();
		?>

		<form action="" method="post">
			<input type="hidden" name="userKey" value="<?php echo $row['userKey'];?>">
			<label>userImage</label><input type="text" name="userImgUrl" value="<?php echo $row['userImgUrl']; ?>"/><br>
			<label>userHeading</label><input type="text" name="userHeading" value="<?php echo $row['userHeading']; ?>"/><br>
			<label>userSubheading</label><input type="text" name="userSubheading" value="<?php echo $row['userSubheading']; ?>"/><br>
			<label>userBody</label><textarea type="text" name="userBody" rows="6" cols="50"/><?php echo $row['userBody']; ?></textarea><br>
			<p>theme:</p>
			<label>light</label><input type="radio" name="userProfileTheme" value="light" <?php if($row['userProfileTheme'] == 'light') { echo 'checked'; } ?>/><br>
			<label>dark</label><input type="radio" name="userProfileTheme" value="dark" <?php if($row['userProfileTheme'] == 'dark') { echo 'checked'; } ?>/><br>
			<label></label><input type="submit" name="submit" value="Save"/><br>
		</form>

		<a href='logout.php'>Logout</a>
		
	</section>

</body>
</html>
