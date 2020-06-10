<?php 
 session_start();
 require_once 'pdo.php';

 $stmt = $pdo->query('SELECT profile_id,user_id,first_name,last_name,email,headline,summary FROM Profile');
 $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<title>Bimal Thapa Magar</title>
 	<link rel="stylesheet" href="">
 	<?php require_once 'bootstrap.php'; ?>
 </head>
 <body>
 	<div class="container">
 		<h1>Bimal Thapa Magar's Resume Registry</h1>
         <?php 
           if (isset($_SESSION['success'])) {
           	   echo '<p style="color:green;">'.$_SESSION['success'].'</p>';
       	        unset($_SESSION['success']);
           }
            if (isset($_SESSION['error'])) {
           	   echo '<p style="color:red;">'.$_SESSION['error'].'</p>';
       	        unset($_SESSION['error']);
           }
          
 		       if (isset($_SESSION['name'])) {
         	
         	 echo '<a href="logout.php">Log Out</a><br>';
            
           } else {
            echo '<p><a href="login.php">Please log in</a><p> <br>';
         }
         	
             if (sizeof($rows) > 0) {
             	echo '<table border="3" class="table table-striped">';
             	echo '<thead><tr>';
              echo '<th>Name</th>';
             	echo '<th>Headline</th>';
             	 if (isset($_SESSION['name'])){
             	echo '<th>Action</th></tr></thead>';
              }

             	foreach ($rows as $row) {
             	  echo '<tr><td>';
             		echo '<a href="view.php?profile_id='.$row['profile_id'].'">';
 					      echo($row['first_name'].$row['last_name']);
             		echo("</a></td><td>");
 					      echo($row['headline']);
             		echo("</td>");
                if (isset($_SESSION['name'])){
                  echo '<td>';
 					         echo '<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a>'.'/'.'<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>';
             		  echo '</td>';
                }
                echo '</tr>';
             	}

             } else {
             	echo '';
             }
             if (isset($_SESSION['name'])) {
                 echo '<a href="add.php">Add New Entry</a>';
             }
             
            
    

         
 		 ?>
    
 	</div>
 </body>
 </html>