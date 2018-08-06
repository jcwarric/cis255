<?php
	session_start();
	require "database.php";
	if($_GET){
		$errorMessage = $_GET['errorMessage'];
	}
	else{
		$errorMessage = '';
	}
	if($_POST){
		$success = false;
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password = MD5($password); //hash password

		echo $username . ' ' . $password ;
	
		//verify the username/password
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql="SELECT * FROM fr_persons WHERE email='$username' AND password = '$password' LIMIT 1";
		$q = $pdo->prepare($sql);
		$q->execute(array());
		$data = $q->fetch(PDO::FETCH_ASSOC);
		echo "<br>RECORD: ";
		//print_r($data);
		//exit();

		if($data){
			//if login successful, go to success.php
			$_SESSION["username"] = $username;
			header("Location: success.php");
			exit();
		}
		else{
			//if login not successful, try to login again
			header("Location: login.php?errorMessage=Invalid"); //*************
			exit();
		}
	}
?>

<h1>Log in</h1>
<form class="form-horizontal" action="login.php" method="post">
	<input name="username" type="text" placeholder="me@email.com">
	<input name="password" type="password" required>
	<button type="submit" class="btn btn-success">Sign in </button>
	<p style='color:red;'><?php echo $errorMessage . "<br/>" ?></p>
	<a href='logout.php'>Log out</a>
	<br>
	<a href='create_person.php'>Join</a>

</form>