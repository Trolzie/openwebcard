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

	<section class="profile-edit">
		
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
			
			<h3>Username</h3>
			<label>username</label><br>
			<input type="text" class="input" name="username" value="<?php echo $userrow['username']; ?>"/><br>
			
			<h3>Your public information</h3>
			<label>userImage</label><br>
			<input type="text" class="input" name="userImgUrl" value="<?php echo $row['userImgUrl']; ?>"/><br>
			<label>userHeading</label><br>
			<input type="text" class="input" name="userHeading" value="<?php echo $row['userHeading']; ?>"/><br>
			<label>userSubheading</label><br>
			<input type="text" class="input" name="userSubheading" value="<?php echo $row['userSubheading']; ?>"/><br>
			<label>userBody</label><br>
			<textarea type="text" class="input" name="userBody" rows="6" cols="50"/><?php echo $row['userBody']; ?></textarea><br>

			<h3>Social links</h3>
			<label>userFacebook</label><br>
			<input type="text" class="input" name="userFacebookUrl" value="<?php echo $socialRow['userFacebookUrl']; ?>"/><br>
			<label>userTwitter</label><br>
			<input type="text" class="input" name="userTwitterUrl" value="<?php echo $socialRow['userTwitterUrl']; ?>"/><br>
			<label>userYoutube</label><br>
			<input type="text" class="input" name="userYoutubeUrl" value="<?php echo $socialRow['userYoutubeUrl']; ?>"/><br>
			<label>userLinkedin</label><br>
			<input type="text" class="input" name="userLinkedinUrl" value="<?php echo $socialRow['userLinkedinUrl']; ?>"/><br>
			<label>userGithub</label><br>
			<input type="text" class="input" name="userGithubUrl" value="<?php echo $socialRow['userGithubUrl']; ?>"/><br>
			<label>userDribbble</label><br>
			<input type="text" class="input" name="userDribbbleUrl" value="<?php echo $socialRow['userDribbbleUrl']; ?>"/><br>
			<label>userInstagram</label><br>
			<div class="social-link instagram" title="Instagram" target="_blank"><svg viewBox="0 0 512 512"><g><path d="M256 109.3c47.8 0 53.4 0.2 72.3 1 17.4 0.8 26.9 3.7 33.2 6.2 8.4 3.2 14.3 7.1 20.6 13.4 6.3 6.3 10.1 12.2 13.4 20.6 2.5 6.3 5.4 15.8 6.2 33.2 0.9 18.9 1 24.5 1 72.3s-0.2 53.4-1 72.3c-0.8 17.4-3.7 26.9-6.2 33.2 -3.2 8.4-7.1 14.3-13.4 20.6 -6.3 6.3-12.2 10.1-20.6 13.4 -6.3 2.5-15.8 5.4-33.2 6.2 -18.9 0.9-24.5 1-72.3 1s-53.4-0.2-72.3-1c-17.4-0.8-26.9-3.7-33.2-6.2 -8.4-3.2-14.3-7.1-20.6-13.4 -6.3-6.3-10.1-12.2-13.4-20.6 -2.5-6.3-5.4-15.8-6.2-33.2 -0.9-18.9-1-24.5-1-72.3s0.2-53.4 1-72.3c0.8-17.4 3.7-26.9 6.2-33.2 3.2-8.4 7.1-14.3 13.4-20.6 6.3-6.3 12.2-10.1 20.6-13.4 6.3-2.5 15.8-5.4 33.2-6.2C202.6 109.5 208.2 109.3 256 109.3M256 77.1c-48.6 0-54.7 0.2-73.8 1.1 -19 0.9-32.1 3.9-43.4 8.3 -11.8 4.6-21.7 10.7-31.7 20.6 -9.9 9.9-16.1 19.9-20.6 31.7 -4.4 11.4-7.4 24.4-8.3 43.4 -0.9 19.1-1.1 25.2-1.1 73.8 0 48.6 0.2 54.7 1.1 73.8 0.9 19 3.9 32.1 8.3 43.4 4.6 11.8 10.7 21.7 20.6 31.7 9.9 9.9 19.9 16.1 31.7 20.6 11.4 4.4 24.4 7.4 43.4 8.3 19.1 0.9 25.2 1.1 73.8 1.1s54.7-0.2 73.8-1.1c19-0.9 32.1-3.9 43.4-8.3 11.8-4.6 21.7-10.7 31.7-20.6 9.9-9.9 16.1-19.9 20.6-31.7 4.4-11.4 7.4-24.4 8.3-43.4 0.9-19.1 1.1-25.2 1.1-73.8s-0.2-54.7-1.1-73.8c-0.9-19-3.9-32.1-8.3-43.4 -4.6-11.8-10.7-21.7-20.6-31.7 -9.9-9.9-19.9-16.1-31.7-20.6 -11.4-4.4-24.4-7.4-43.4-8.3C310.7 77.3 304.6 77.1 256 77.1L256 77.1z"/><path d="M256 164.1c-50.7 0-91.9 41.1-91.9 91.9s41.1 91.9 91.9 91.9 91.9-41.1 91.9-91.9S306.7 164.1 256 164.1zM256 315.6c-32.9 0-59.6-26.7-59.6-59.6s26.7-59.6 59.6-59.6 59.6 26.7 59.6 59.6S288.9 315.6 256 315.6z"/><circle cx="351.5" cy="160.5" r="21.5"/></g></svg><!--[if lt IE 9]><em>Instagram</em><![endif]--></div>
			<input type="text" class="input" name="userInstagramUrl" value="<?php echo $socialRow['userInstagramUrl']; ?>"/><br>

			<p>theme:</p>
			<label>light</label><input type="radio" name="userProfileTheme" value="light" <?php if($row['userProfileTheme'] == 'light') { echo 'checked'; } ?>/><br>
			<label>dark</label><input type="radio" name="userProfileTheme" value="dark" <?php if($row['userProfileTheme'] == 'dark') { echo 'checked'; } ?>/><br>

			<input type="submit" name="submit" value="Save"/><br>
		</form>

		<a href='logout.php'>Logout</a>
		
	</section>

</body>
</html>
