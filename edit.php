<?php 
 session_start();
require_once 'pdo.php';

if (isset($_POST['cancel'])) {
	header("Location:index.php");
	return;
}

if (!isset($_SESSION['name'])) {
 	die('Not Logged In');
 }
 if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
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
            return "Year must be numeric";
        }
    }
    return true;
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
	
 	  if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
 	  	    $_SESSION['error'] = "All fields are required";
 	  	    header("Location:edit.php?profile_id=".$_REQUEST['profile_id']);
 	  	    return;
 	  }

 	  if (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
    		$_SESSION['error'] = "Email address must contain at @";
    		header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
    		return;
    	}

      if (! validatePos()) {
        $_SESSION['error'] = validatePos();
        header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
        return;
      } 
    

    	$sql = "UPDATE Profile SET user_id = :uid,first_name= :fn , last_name = :ln, email=:em ,headline=:he ,summary=:su WHERE profile_id = :pid";
    	$stmt =  $pdo->prepare($sql);
    	$stmt->execute(array(
 			':uid' => $_SESSION['user_id'],
   			':fn' => $_POST['first_name'],
   			':ln' => $_POST['last_name'],
   			':em' => $_POST['email'],
   			':he' => $_POST['headline'],
   			'su' => $_POST['summary'],
   			':pid' => $_REQUEST['profile_id'],

    	));

       $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
       $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

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
                    ':pid' => $_REQUEST['profile_id'],
                    ':rank' => $rank,
                    ':year' => $year,
                    ':desc' => $desc)
            );

            $rank++;

        }


    	$_SESSION['success'] = 'Profile Updated';
    	header("Location:index.php");
    	return;
 } 


$stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :xyz');
$stmt->execute(array(
    ':xyz' => $_GET['profile_id'],
));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$st = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz ORDER BY rank");
$st->execute(array(":xyz" => $_GET['profile_id']));
$positions = $st->fetchAll(PDO::FETCH_ASSOC);

// if ($positions == false) {
//    $_SESSION['error'] = "Can't update profile";
//    header("Location:edit.php?profile_id=".$_REQUEST['profile_id']);
//    return;
// }




 ?>


 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<title>Bimal Thapa Magar's Edit Profile Screen</title>
 	<link rel="stylesheet" href="">
 	<?php require_once 'bootstrap.php'; ?>
 </head>
 <body>
 	 <div class="container">
 	 	<div class="row">
 			<div class="col-md-9 col-md-offset-2">
 				<div class="panel panel-default">
 					<div class="panel-heading">
 	 					<h1 class="panel-title">Edit Profile For <?= $_SESSION['name']; ?></h1>
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
 	  	 	<input type="text" name="first_name" class="form-control" id="first_name" value="<?= htmlentities($row['first_name'])?>"/><br>

 	  	 	<label for="last_name">Last Name:</label>
 	  	 	<input type="text" name="last_name" id="last_name" class="form-control" value="<?= htmlentities($row['last_name'])?>"> <br>

 	  	 	<label for="email">Email</label>
 	  	 	<input type="text" name="email" id="email" class="form-control" value="<?= htmlentities($row['email'])?>"><br>

 	  	 	<label for="headline">Headline</label>
 	  	 	<input type="text" name="headline" id="headline" class="form-control" value="<?= htmlentities($row['headline'])?>"><br>

 	  	 	<label for="summary">Summary:</label><br>
 	  	 	<textarea name="summary" id="summary" class="form-control" cols="80" rows="8"><?= htmlentities($row['summary'])?></textarea><br>
         
         <?php 
            $pos = 0;
            echo('<p>Position: <input type="submit" id="addPos" value="+"><br>');
            echo('<div id="position_fields"><br>');
            foreach ($positions as $position) {
                  $pos++;
                  echo('<div id="position'.$pos.'"><br>');
                  echo('<p>Year:<input type="text" name="year'.$pos.'" value="'.$position['year'].'">');
                  echo('<input type="button" value="-" onclick="$(\'#position'.$pos.'\').remove();return false;"<br>'); 
                  echo('<p><br>');
                  echo('<textarea name="desc'.$pos.'" rows="8" cols="80">');
                  echo(htmlentities($position['description']));
                  echo('</textarea><br></div><br>');

            }
            echo('</div></p><br>');
            
          ?>

 	  	 	<input type="hidden" name="profile_id" value="<?= htmlentities($row['profile_id'])?>">



 	  	 	<input type="submit" class="btn btn-default" value="Save">
 	  	 	<input type="submit" class="btn btn-default" name="cancel" value="Cancel">
 	 	</form>
 	 </div>
 	</div>
 </div>
</div>
 	 </div>
     <script>
            countPos = <?= $pos ?>;

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