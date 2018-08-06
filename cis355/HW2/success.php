<?php
	session_start(); //get access to $_SESSION
	if(!isset($_SESSION["username"])){
		header("Location: login.php");
	}
	else{
		header("Location: index.php");
		echo "success";
	}

?>
