<?php
//include config
require_once('../config.php');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
	echo "logged in, " . "user id: " . $_SESSION['userID'];
} else {
	echo "logged out";
}

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: ../index.php'); }

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Profile</title> <!-- add username once established -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
						//insert into owc_users table
						$userstmt = $db->prepare('UPDATE owc_users SET username = :username WHERE userID = :userKey');
						$userstmt->execute(array(
							':username' => $username,
							':userKey' => $userKey
						));
					} catch(PDOException $e) {
						//echo $e->getMessage();
						if ($e->errorInfo[1] == 1062) {
							echo 'The username: "' . $username . '" already exists, please try another';
						}
					}

					try {
						//insert into owc_userdata table
						$stmt = $db->prepare('UPDATE owc_userdata SET userImgUrl = :userImgUrl, userHeading = :userHeading, userSubheading = :userSubheading, userBody = :userBody, userProfileTheme = :userProfileTheme WHERE userKey = :userKey');
						$stmt->execute(array(
							':userImgUrl' => $userImgUrl,
							':userHeading' => $userHeading,
							':userSubheading' => $userSubheading,
							':userBody' => $userBody,
							':userProfileTheme' => $userProfileTheme,
							':userKey' => $userKey
						));
					} catch(PDOException $e) {
						// echo $e->getMessage();
					}

					try {
						//insert into owc_usersocial table
						$socialstmt = $db->prepare('UPDATE owc_usersocial SET userFacebookUrl = :userFacebookUrl, userTwitterUrl = :userTwitterUrl, userYoutubeUrl = :userYoutubeUrl, userLinkedinUrl = :userLinkedinUrl, userGithubUrl = :userGithubUrl, userDribbbleUrl = :userDribbbleUrl, userInstagramUrl = :userInstagramUrl WHERE userKey = :userKey');
						$socialstmt->execute(array(
							':userFacebookUrl' => $userFacebookUrl,
							':userTwitterUrl' => $userTwitterUrl,
							':userYoutubeUrl' => $userYoutubeUrl,
							':userLinkedinUrl' => $userLinkedinUrl,
							':userGithubUrl' => $userGithubUrl,
							':userDribbbleUrl' => $userDribbbleUrl,
							':userInstagramUrl' => $userInstagramUrl,
							':userKey' => $userKey
						));
					} catch(PDOException $e) {
						// echo $e->getMessage();
					}

					// redirect to index page
					// header('Location: index.php?action=updated');
					// header('Location: index.php');
					// exit;
				}
			}

		?>

		<?php
			$userID = $_SESSION['userID'];
			$userstmt = $db->prepare('SELECT username FROM owc_users WHERE userID = :userID');
			$userstmt->execute(array(':userID' => $_SESSION['userID']));
			$userrow = $userstmt->fetch();

			$stmt = $db->prepare('SELECT userImgUrl, userHeading, userSubheading, userBody, userProfileTheme, userKey FROM owc_userdata WHERE userKey = :userKey');
			$stmt->execute(array(':userKey' => $_SESSION['userID']));
			$row = $stmt->fetch();

			$socialstmt = $db->prepare('SELECT userFacebookUrl, userTwitterUrl, userYoutubeUrl, userLinkedinUrl, userGithubUrl, userDribbbleUrl, userInstagramUrl FROM owc_usersocial WHERE userKey = :userKey');
			$socialstmt->execute(array(':userKey' => $_SESSION['userID']));
			$socialRow = $socialstmt->fetch();
		?>

		<form action="" method="post">
			<input type="hidden" name="userKey" value="<?php echo $row['userKey'];?>">
			
			<label>username</label><input type="text" name="username" value="<?php echo $userrow['username']; ?>"/><br>
			
			<label>userImage</label><input type="text" name="userImgUrl" value="<?php echo $row['userImgUrl']; ?>"/><br>
			<label>userHeading</label><input type="text" name="userHeading" value="<?php echo $row['userHeading']; ?>"/><br>
			<label>userSubheading</label><input type="text" name="userSubheading" value="<?php echo $row['userSubheading']; ?>"/><br>
			<label>userBody</label><textarea type="text" name="userBody" rows="6" cols="50"/><?php echo $row['userBody']; ?></textarea><br>
			<p>theme:</p>
			<label>light</label><input type="radio" name="userProfileTheme" value="light" <?php if($row['userProfileTheme'] == 'light') { echo 'checked'; } ?>/><br>
			<label>dark</label><input type="radio" name="userProfileTheme" value="dark" <?php if($row['userProfileTheme'] == 'dark') { echo 'checked'; } ?>/><br>

			<label>userFacebook</label><input type="text" name="userFacebookUrl" value="<?php echo $socialRow['userFacebookUrl']; ?>"/><br>
			<label>userTwitter</label><input type="text" name="userTwitterUrl" value="<?php echo $socialRow['userTwitterUrl']; ?>"/><br>
			<label>userYoutube</label><input type="text" name="userYoutubeUrl" value="<?php echo $socialRow['userYoutubeUrl']; ?>"/><br>
			<label>userLinkedin</label><input type="text" name="userLinkedinUrl" value="<?php echo $socialRow['userLinkedinUrl']; ?>"/><br>
			<label>userGithub</label><input type="text" name="userGithubUrl" value="<?php echo $socialRow['userGithubUrl']; ?>"/><br>
			<label>userDribbble</label><input type="text" name="userDribbbleUrl" value="<?php echo $socialRow['userDribbbleUrl']; ?>"/><br>
			<label>userInstagram</label><input type="text" name="userInstagramUrl" value="<?php echo $socialRow['userInstagramUrl']; ?>"/><br>
			<label></label><input type="submit" name="submit" value="Save"/><br>
		</form>

		<a href='logout.php'>Logout</a>
		
	</section>

</body>
</html>
