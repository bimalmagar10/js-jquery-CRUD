<?php 
session_start();
require_once "pdo.php";

$salt ='XyZzy12*_';
if(isset($_POST['cancel'])){
	header("Location:index.php");
	return;
}
    if (isset($_POST['email']) && isset($_POST['pass'])) {
    
    	if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
    		$_SESSION['error'] = "All values are required";
    		header("Location: login.php");
    		return;
    	}

    	if (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
    		$_SESSION['error'] = "Email address must contain at the rate (@)";
    		header("Location: login.php");
    		return;
    	}
        
    	$check = hash('md5',$salt.$_POST['pass']);
    	$stmt = $pdo->prepare('SELECT user_id,name FROM users WHERE email= :em AND password= :pw');

    	$stmt->execute(array(
          ':em' => $_POST['email'],
          ':pw' => $check,
    	));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
    	if ($row !== false ) {
    		$_SESSION['name'] = $row['name'];
    		$_SESSION['user_id'] = $row['user_id'];
    		header("Location:index.php");
    		return;
    	} else {
    		$_SESSION['error'] = "Incorrect Password";
    		header("Location:login.php");
    		return;
    	}
    	
    }
    
 ?>



 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<title>Bimal Thapa Magar's Login Screen </title>
 	<link rel="stylesheet" href="">
 	<?php require_once 'bootstrap.php'; ?>
 </head>
 <body>
 	<div class="container" >
 		<div class="row">
 			<div class="col-md-5 col-md-offset-3">
 					<div class="panel panel-default">
 						<div class="panel-heading">
 	                      <h1 class="panel-title">Please Log In</h1>
 	                    </div>
 	<?php 
       if (isset($_SESSION['error'])) {
       	  echo '<p style="color:red;">'.$_SESSION['error'].'</p>';
       	  unset($_SESSION['error']);
       }
 	 ?>
 	<div class="panel-body">
 	<form action="" method="POST">
 		<fieldset>
 		<label for="email">Email:</label>
 		<input type="text" id="email" name="email" class="form-control" /><br>
 		<label for="id_1723">Password:</label>
 		<input type="text" name="pass" id="id_1723" class="form-control"><br>
 		<input type="submit" onclick="return doValidate();" class=" btn btn-default" value="Log In">
 		<input type="submit" value="Cancel" class=" btn btn-default" name="cancel"/>
 	   </fieldset>
 	</form>
 	<p>For a password hint, view source and find an account and password hint in the HTML comments</p>
 </div>
</div>
 	</div>
 </div>
 	</div>
 	<script>
 		
 		
 		function doValidate(){
 			console.log('Validating....');
 			try {
 				var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
 				var password = document.getElementById('id_1723').value;
 				var email = document.getElementById('email').value;
 				if(password == null || password == ''){
 					alert('Both fields must be filled out');
 					return false;
 				}
 				if (! (email.match(mailformat))) {
 					alert('Invalid email address');
 					return false;
 				}

 				return true;
 			} catch(e){
 				return false;
 			}
 			return false;
 		}

 	</script>
 </body>
 </html>