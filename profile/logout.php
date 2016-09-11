<?php
//include config
require_once('../config.php');

//log user out
$user->logout();
header('Location: ../index.php'); 

?>