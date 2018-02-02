<?php

session_start();

require 'database.php';

if(isset($_SESSION['user_id']))
{
	$records = $conn->prepare('SELECT id,email,password FROM users WHERE email = :email');
	$records->bindParam(':email', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);

	$user = null;

	if(count($results) > 0)
	{
		$users = $results;
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
		<?php if(!isset($_SESSION['user_id'])): ?>
			<meta http-equiv="refresh" content="0; url=login.php"/>
		<?php endif; ?>
	</head>
	<body>
		<div class="header">
			<a href="index.php">Home</a>
		</div>

		<?php if(isset($_SESSION['user_id'])): ?>
			<p> Welcome <?=$user['email'];?> </p>
			<p>Student Number: <?=$_SESSION['user_id']?></p>
			<table><tr><td>Item Name</td><td>Cost</td><td>Delete</td><tr>
				<?php
					$result = $conn->query('SELECT item,cost,discount FROM items');
					if ($result->rowCount() > 0)
					{
						while($row = $result->fetch(PDO::FETCH_ASSOC))
						{
								$item = $row["item"];
								$price = '$' . number_format($row["cost"],2);
								if($row["discount"] > 0)
								{
									$price = "Original: {$price}  | Discount: $" . number_format($row["cost"]*(1 - ($row["discount"]/100.0)),2) . " at " . $row["discount"] . "% off" ;
								}
								echo "<tr><td>" . $row["item"] ." </td><td>" . $price . '</td><td> <a href="order.php?id='. $item . '">Order ' . $item . '</a></td></tr>';
						}
					}
					else
					{
						echo "<tr>No items in the database found</tr>";
					}
				?>
			</table>
			<a href="logout.php"> Log-out </a>
		<?php else: ?>

			<h1> Please Login or Register</h1>
			<a href="login.php">Login</a> or
			<a href="register.php">Register</a>

		<?php endif; ?>
	</body>
</html>
