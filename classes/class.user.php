<?php

include('class.password.php');

class User extends Password{

	private $db;

	function __construct($db){
		parent::__construct();

		$this->_db = $db;
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}
	}

	private function get_user_hash($email){

		try {

			$stmt = $this->_db->prepare('SELECT userID, password FROM owc_users WHERE email = :email');
			$stmt->execute(array('email' => $email));

			$row = $stmt->fetch();
			return $row;

		} catch(PDOException $e) {
			echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}


	public function login($email,$password){

		$userHash = $this->get_user_hash($email);
		$hashed = $userHash['password'];

		if($this->password_verify($password,$hashed) == 1){

			$_SESSION['loggedin'] = true;
			$_SESSION['userID'] = $userHash['userID'];
			return true;
		}
	}


	public function logout(){
		session_destroy();
	}

}


?>