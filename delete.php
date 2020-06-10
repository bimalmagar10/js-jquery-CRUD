<?php 
session_start();
require_once 'pdo.php';

if (! isset($_SESSION['name'])) {
	die('Not Logged In');
}
if (isset($_POST['cancel'])) {
	header("Location:index.php");
	return;
}

if (! isset($_GET['profile_id'])) {
	$_SESSION['error'] = "Missing profile_id";
	header("Location:index.php");
	return;
}

if (isset($_POST['delete']) && isset($_POST['profile_id'])) {
	  
	  $sql = "DELETE FROM Profile WHERE profile_id=:zip";

	  $stmt = $pdo->prepare($sql);
	  $stmt->execute(array(
     		':zip' => $_POST['profile_id']
	  ));
	  $_SESSION['success'] = "Record Deleted";
	  header("Location:index.php");
	  return;
}




$stmt = $pdo->prepare('SELECT first_name,last_name FROM Profile WHERE profile_id = :xyz');
$stmt->execute(array(
	':xyz' => $_REQUEST['profile_id'],
));
$row = $stmt->fetch(PDO::FETCH_ASSOC);



 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Bimal Thapa Magar's delete Profile Page</title>
	<link rel="stylesheet" href="">
	<?php require_once 'bootstrap.php'; ?>
</head>
<body>
	<div class="container">
		<div class="row">
 			<div class="col-md-9 col-md-offset-2">
 				<div class="panel panel-default">
 					<div class="panel-heading">
						<h1 class="panel-title">Confirm Deletion of Profile Id= <?= $_REQUEST['profile_id']?></h1>
				    </div>
				    <div class="panel-body">		
						<p>First Name:<?= $row['first_name']; ?></p>
						<p>Last Name: <?= $row['last_name']; ?></p>
						<form action="" method="POST">
							<input type="hidden" name="profile_id" value="<?= $_REQUEST['profile_id']?>">
							<input type="submit" name="delete" class="btn btn-default"value="Delete">
							<input type="submit" name="cancel" class=" btn btn-default"value="Cancel">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>