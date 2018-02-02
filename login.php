<?php
	session_start();

	if(isset($_SESSION['user_id']))
	{
		header("Location: index.php");
	}

	require 'database.php';
	require_once "phplibs/recaptchalib.php";

	if(!empty($_POST['email']) && !empty($_POST['password']))
	{
		//records finds email from the query
		$records = $conn->prepare('SELECT id,email,password FROM users WHERE email = :email');
		$records->bindParam(':email',$_POST['email']);
		$records->execute();
		$results = $records->fetch(PDO::FETCH_ASSOC);
		$message = '';
		if(count($results) > 0 && password_verify($_POST['password'], $results['password']))
		{
			$_SESSION['user_id'] = $results['id'];
			header("Location: index.php");
		}
		else
		{
			$message = 'Sorry. We could not find your userdata.';
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title> Login Below! </title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
		<div class="header">
			<a href="index.php">Home</a>
		</div>
		<main>
			<div class="form">
				<h1> Please Login</h1>
				<?php if(!empty($message)): ?>
					<p><?= $message ?></p>
				<?php endif; ?>
				<form action="login.php" method="POST">
					<input type="text" placeholder="Enter your Email" name="email"> </input>
					<input type="password" placeholder="Enter your Password" name="password"></input>

					<input type="submit" value="Login"></input>
					<!--<div class="g-recaptcha captcha" data-sitekey="[Insert Key here]"></div>-->
					<p><a href="register.php">Need an account? Register here!</a></p>
				</form>
			</div>
		</main>
	</body>
</html>
