<?php

session_start();

$message = '';

require 'database.php';

if(!isset($_SESSION['user_id']))
{
	header("Location: admin.php");
}
if(!empty($_POST['item']) && !empty($_POST['cost']))
{
	$sql = "INSERT INTO items (item,cost,discount) VALUES (:item, :cost, :discount)";
	$stmt = $conn->prepare($sql);

	$stmt->bindParam(':item',$_POST['item']);
	$stmt->bindParam(':cost',$_POST['cost']);
	$stmt->bindParam(':discount',$_POST['discount']);

	if( $stmt->execute() )
	{
		$message = 'Successfully created new item';
		header("Location: admin-client.php");
		$sqlo = "SELECT * FROM items ORDER BY item ASC";
		$stmto = $conn->prepare($sql);
	}
	else
	{
		$message = 'Sorry there must was an issue adding your item.';
	}
}
elseif (isset($_POST['add']))
{
	$message = 'Please enter data in require fields.';
}
?>

<!DOCTYPE hmtl>
<html>
	<head>
		<title> Admin Client </title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<script src="js/jquery-3.2.1.min.js"></script>
	</head>
	<body>
		<div class="header">
			<a href="index.php">Home</a>
		</div>

		<?php if(isset($_SESSION['user_id'])): ?>
			<p> Welcome to the Admin Client </p>
        <form action="admin-client.php" method="post">
					<p> <?php echo "$message" ?> </p>
          <input class="addItem" type="text" placeholder="Add a new item (Required)" name="item"/>
          <input class="addItem" type="number" step="0.01" placeholder="Enter a price (Required)" name="cost"/>
          <input class="addItem" type="number" placeholder="Enter a discount percentage" name="discount"/>
					<input class="addItem buttonSmall" type="submit" name="add" value="Add"></input>
        </form>
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
									echo "<tr><td>" . $row["item"] ." </td><td>" . $price . '</td><td> <a href="delete.php?id='. $item . '">Delete ' . '</a > &nbsp;  <a href="edit.php?id='. $item. '">Edit</a></td></tr>';
							}
						}
						else
						{
							echo "<tr>No items in the database found</tr>";
						}
					?>
				</table>
			<a href="logout.php"> Log-out </a>
		<?php
    else:
        header("Location: index.php");
    ?>

		<?php endif; ?>
	</body>
</html>
