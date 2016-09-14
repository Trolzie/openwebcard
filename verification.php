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

	echo $code;

	$stmt = $db->prepare('SELECT email FROM owc_users WHERE userID = :userID AND activated = :activated');
	$stmt->execute(array(
		':userID' => $id,
		':activated' => $code
	));
	$row = $stmt->fetch();


	if(count($row)==1) {
		echo "WORKING!";
	} else {
		echo "NOT WORKING!";
	}

	echo count($row);

	// mysql_connect('localhost','root','');
	// mysql_select_db('sample');
	// $select=mysql_query("select email,password from verify where id='$id' and code='$code'");
	// if(mysql_num_rows($select)==1)
	// {
	// 	while($row=mysql_fetch_array($select))
	// 	{
	// 		$email=$row['email'];
	// 		$password=$row['password'];
	// 	}
	// 	$insert_user=mysql_query("insert into verified_user values('','$email','$password')");
	// 	$delete=mysql_query("delete from verify where id='$id' and code='$code'");
	// }
}

?>