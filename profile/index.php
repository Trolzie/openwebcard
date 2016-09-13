<?php require_once('../config.php');

if ($_SESSION['loggedin'] == 1) {
	echo "logged in, " . "user id: " . $_SESSION['userID'];
} else {
	echo "logged out";
}

//if not logged in redirect to login page

// if(!$user->is_logged_in()){ header('Location: ../index.php'); }

$userID = $_SESSION['userID'];
$stmt = $db->prepare('SELECT userHeading, userSubheading, userBody, userKey FROM owc_userdata WHERE userKey = :userKey');
$stmt->execute(array(':userKey' => $_SESSION['userID']));
$row = $stmt->fetch();
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Profile</title> <!-- add username once established -->
	<!-- <link rel="stylesheet" href="../css/style.css"> -->
</head>
<body>

	<h1>Open Web Card Profile</h1>

	<p>welcome to your profile. Before we can create an online businesscard, we need to find out what you want to display.</p>

	<h2>Your current profile:</h2>

	<?php

		if(isset($_POST['submit'])){

			$_POST = array_map( 'stripslashes', $_POST );

			//collect form data
			extract($_POST);

			// if($postID ==''){
			// 	$error[] = 'This post is missing a valid id!.';
			// }

			// if($postTitle ==''){
			// 	$error[] = 'Please enter the title.';
			// }

			// if($postDesc ==''){
			// 	$error[] = 'Please enter the description.';
			// }

			// if($postCont ==''){
			// 	$error[] = 'Please enter the content.';
			// }

			// echo $userHeading;
			// echo $userSubheading;
			// echo $userBody;
			// echo "-   ";
			// echo $userKey;
			// echo $row['userKey'];
			// echo "   -";

			if(!isset($error)){

				try {

					//insert into database
					$stmt = $db->prepare('UPDATE owc_userdata SET userHeading = :userHeading, userSubheading = :userSubheading, userBody = :userBody, WHERE userKey = :userKey') ;
					$stmt->execute(array(
						':userHeading' => $userHeading,
						':userSubheading' => $userSubheading,
						':userBody' => $userBody,
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

	

	<form action="" method="post">
		<input type="hidden" name="userKey" value="<?php echo $row['userKey'];?>">
		<label>userHeading</label><input type="text" name="userHeading" value="<?php echo $row['userHeading']; ?>"/><br>
		<label>userSubheading</label><input type="text" name="userSubheading" value="<?php echo $row['userSubheading']; ?>"/><br>
		<label>userBody</label><textarea type="text" name="userBody" rows="6" cols="50"/><?php echo $row['userBody']; ?></textarea><br>
		<label></label><input type="submit" name="submit" value="Save"/><br>
	</form>

	<a href='logout.php'>Logout</a>

</body>
</html>
