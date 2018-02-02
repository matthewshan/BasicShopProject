<?php
session_start();

if(isset($_SESSION['user_id']))
{
  header("Location: admin-client.php");
}

require 'database.php';

//Login
$message = '';
if(!isset($_SESSION['user_id']) && !empty($_POST['password']))
{
  $records = $conn->prepare('SELECT id,email,password FROM users WHERE email = "admin@admin.com"');
  $records->bindParam(':email',$_POST['email']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);
  if(count($results) > 0 && password_verify($_POST['password'], $results['password']))
  {
    $_SESSION['user_id'] = $results['id'];
    header("Location: admin-client.php");
  }
  else
  {
    $message = 'Sorry. We could not find your userdata.';
  }
}
?>

<!DOCTYPE hmtl>
<html>
	<head>
		<title> Welcome to your login! </title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
	<body>
		<div class="header">
			<p>Admin Client</p>
		</div>
    <?php if(isset($_SESSION['user_id'])): ?>
      You have been logged in.
    <?php else:?>
      <div class="form">
        <h2> Admin Login </h2>
        <p><?= $message ?></p>
        <form action="admin.php" method="post">
          <input type="password" placeholder="Enter your Password" name="password"></input>
          <input type="submit" value="Login"></input>
        </form>
      </div>
    <?php endif; ?>
	</body>
</html>
