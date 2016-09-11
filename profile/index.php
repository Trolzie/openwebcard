<?php require_once('../config.php');
echo "user id: " . $_SESSION['userID'] . ", ";
echo "logged in: " . $_SESSION['loggedin'];
//if not logged in redirect to login page

// if(!$user->is_logged_in()){ header('Location: ../index.php'); }

$stmt = $db->prepare('SELECT userHeading, userSubheading, userBody FROM owc_userdata WHERE userdataID = :postID');
$stmt->execute(array(':postID' => $_SESSION['userID']));
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

	<form action="" method="post">
		<label>Name</label><input type="text" name="name" value="<?php echo $row['userHeading']; ?>"/><br>
		<label>Heading</label><input type="text" name="heading" value="<?php echo $row['userSubheading']; ?>"/><br>
		<label>Body</label><textarea type="text" name="body" rows="6" cols="50"/><?php echo $row['userBody']; ?></textarea><br>
		<label></label><input type="submit" name="submit" value="Save"/><br>
	</form>

	<a href='logout.php'>Logout</a>

</body>
</html>
