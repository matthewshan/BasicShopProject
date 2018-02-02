<?php
session_start();

if(isset($_SESSION['user_id;']))
{
	header("Locaation: index.php");
}

require 'database.php';

$message = '';
if(!empty($_POST['id']) && !empty($_POST['password']) && !empty($_POST['email']) && !empty($_POST['confirm_password']))
{
	$sql = "INSERT INTO users (id,email,password) VALUES (:id, :email, :password)";
	$stmt = $conn->prepare($sql);

	$passtemp = password_hash($_POST['password'],PASSWORD_BCRYPT);
	$stmt->bindParam(':email',$_POST['email']);
	$stmt->bindParam(':id',$_POST['id']);
	$stmt->bindParam(':password',$passtemp);

	if( $stmt->execute() )
	{
		//var_dump($conn);
		$message = 'Successfully created new user';
		header("Location: login.php");
	}
	else
	{
		$message = 'Sorry there must was an issue creating your account.';
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title> Register below! </title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
		<div class="header">
			<a href="index.php">Home</a>
		</div>

		<?php if(!empty($message)): ?>
			<p><?= $message ?></p>
		<?php endif; ?>

		<div class="form">
				<h1> Please Register</h1>
				<form action="register.php" method="POST">
					<input type="text" title="E-Mail" placeholder="Enter your Email" name="email"> </input>
					<input type="text" title="ID" placeholder="Enter your Student ID" name="id"> </input>
					<input type="password" title="Password" placeholder="Enter your Password" name="password"></input>
					<input type="password" placeholder="Confirm your Password" name="confirm_password"></input>
					<input type="submit" value="Register"></input>
					</br><p><a href="login.php">Have an account? Login here!</a></p>
				</form>
			</div>
	</body>
</html>
