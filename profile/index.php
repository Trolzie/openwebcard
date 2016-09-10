<?php require_once('../config.php');
echo "user id: " . $_SESSION['userID'] . ", ";
echo "logged in: " . $_SESSION['loggedin'];
//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }

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

	<p>welcome to you profile. Before we can create an online businesscard, we need to find out what you want to display.</p>

	<h2>Your current profile:</h2>

	<p><?php echo $row['userHeading']; ?></p>
	<p><?php echo $row['userSubheading']; ?></p>
	<p><?php echo $row['userBody']; ?></p>

	<form action="" method="post">
		<label>Name</label><input type="text" name="name" value=""  /><br>
		<label>Heading</label><input type="text" name="heading" value=""  /><br>
		<label>Body</label><textarea type="text" name="body" value=""  /></textarea><br>
		<label></label><input type="submit" name="submit" value="Save"  /><br>
	</form>

</body>
</html>
