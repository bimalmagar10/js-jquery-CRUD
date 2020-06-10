<?php 
 session_start();
 require_once 'pdo.php';
 if (!isset($_SESSION['name'])) {
 	die('Not Logged In');
 }

 if (isset($_POST['cancel'])) {
 	header("Location:index.php");
 	return;
 }
  
 function validatePos()
{
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];

        if (strlen($year) == 0 || strlen($desc) == 0) {
            return "All fields are required";
        }

        if (!is_numeric($year)) {
            return "Position year must be numeric";
        }
    }
    return true;
}
 

 if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
 	  if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
 	  	    $_SESSION['error'] = "All fields are required";
 	  	    header("Location:add.php");
 	  	    return;
 	  }

 	  if (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
    		$_SESSION['error'] = "Email address must contain @";
    		header("Location: add.php");
    		return;
    	}

      if (validatePos() !== true) {
        $_SESSION['error'] = validatePos();
        header("Location: add.php");
        return;
      } 
      

    	$sql = "INSERT INTO Profile (user_id,first_name,last_name,email,headline,summary) VALUES (:uid,:fn,:ln,:em,:he,:su)";
    	$stmt = $pdo->prepare($sql);
    	$stmt->execute(array(
   			':uid' => $_SESSION['user_id'],
   			':fn' => $_POST['first_name'],
   			':ln' => $_POST['last_name'],
   			':em' => $_POST['email'],
   			':he' => $_POST['headline'],
   			'su' => $_POST['summary'],
    	));
     
       $profile_id = $pdo->lastInsertId();
     
        
        $rank = 1;
        for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;

            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];
            $stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description)
            VALUES ( :pid, :rank, :year,:desc)');

            $stmt->execute(array(
                    ':pid' => $profile_id,
                    ':rank' => $rank,
                    ':year' => $year,
                    ':desc' => $desc)
            );

            $rank++;

        }

    	$_SESSION['success'] = "Profile added";
    	header("Location:index.php");
    	return;
    
    
 } 


 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<title>Bimal Thapa Magar's Profile Add</title>
 	<link rel="stylesheet" href="">
 	<?php require 'bootstrap.php'; ?>
 </head>
 <body>
 	  <div class="container">
 	  	<div class="row">
 			<div class="col-md-9 col-md-offset-2">
 				<div class="panel panel-default">
 					<div class="panel-heading">
                      <h1 class="panel-title">Adding profile for <?=$_SESSION['name']; ?></h1>
                    </div>
 	  	 <?php 
    		if (isset($_SESSION['error'])) {
    			echo '<p style="color:red;">'.$_SESSION['error'].'</p>';
       	        unset($_SESSION['error']);
    		}
 	  	  ?>
 	  	  <div class="panel-body">
 	  	 <form action="" method="POST">
 	  	 	<label for="first_name">First Name:</label>
 	  	 	<input type="text" name="first_name" class="form-control" id="first_name"/><br>

 	  	 	<label for="last_name">Last Name:</label>
 	  	 	<input type="text" name="last_name" id="last_name" class="form-control"> <br>

 	  	 	<label for="email">Email</label>
 	  	 	<input type="text" name="email" id="email" class="form-control"><br>

 	  	 	<label for="headline">Headline</label>
 	  	 	<input type="text" name="headline" id="headline" class="form-control"><br>

 	  	 	<label for="summary">Summary:</label><br>
 	  	 	<textarea name="summary" id="summary" cols="80" rows="8" class="form-control"></textarea><br>
         
         <p>
            Position: <input type="submit" id="addPos" value="+">
          <div id="position_fields">
           
          </div>
        </p> 


 	  	 	<input type="submit" class="btn btn-default" value="Add">
 	  	 	<input type="submit" name="cancel" class="btn btn-default" value="Cancel">
 	  	 </form>
	 	  	</div>
	 	  </div>
	 	</div>
	 </div>
	</div>
    <script>
        countPos = 0;

        // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
        $(document).ready(function () {
            window.console && console.log('Document ready called');
            $('#addPos').click(function (event) {
                // http://api.jquery.com/event.preventdefault/
                event.preventDefault();
                if (countPos >= 9) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position " + countPos);
                $('#position_fields').append(
                    '<div id="position' + countPos + '"> \
            <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position' + countPos + '\').remove();return false;"></p> \
            <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
            </div>');
            });
        });
    </script>
 </body>
 </html>