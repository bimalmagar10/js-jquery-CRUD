<?php 
 session_start();
 require_once 'pdo.php';
 
 if (!isset($_GET['profile_id'])) {
 	$_SESSION['error'] = "Missing profile_id";
 	header("Location:index.php");
 	return;
 }

  $stmt =  $pdo->prepare('SELECT * FROM Profile WHERE profile_id= :xyz ');
  $stmt->execute(array(
     ':xyz'=> $_REQUEST['profile_id'],
  ));

  $row =  $stmt->fetch(PDO::FETCH_ASSOC); 
 
  $st =  $pdo->prepare('SELECT * FROM Position WHERE profile_id= :xyz ');
   $st->execute(array(
     ':xyz'=> $_REQUEST['profile_id'],
  ));
   $rows = $st->fetchAll(PDO::FETCH_ASSOC); 
   
   

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<title>Bimal Thapa Magar's Profile View</title>
 	<link rel="stylesheet" href="">
 	<?php require_once 'bootstrap.php' ?>
 </head>
 <body>
 	 <div class="container">
 	 	<div class="row">
 			<div class="col-md-6 col-md-offset-3">
 				<div class="panel panel-default">
 					<div class="panel-heading">
			 	 	<h1 class="panel-title">Profile Information</h1>
			 	    </div>	
			 	    <div class="panel-body">
			 	 	 <p class="text-center">First Name:<?= $row['first_name'] ?></p>
			 	 	 <p class="text-center">Last Name:<?= $row['last_name']?></p>
			 	 	 <p class="text-center">Email:<?= $row['email']?></p>
			 	 	 <p class="text-center">Headline:<?= $row['headline'] ?></p>
			 	 	 <p class="text-center">Summary:<?= $row['summary']?></p>
			 	 	  <p class="text-center">Position:</p>
 							<?php foreach ($rows as $row): ?>
 								<li class="text-center"><?= $row['year'].':'.$row['description']; ?></li>
 							<?php endforeach ?>
			 	 	 <a href="index.php" class="btn btn-default">Done</a>
			 	 	</div>
			 	 	</div>
			 	 </div>
			 	</div>
 	 </div>
 </body>
 </html>