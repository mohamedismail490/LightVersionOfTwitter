<?php

	include("functions.php");

	if ($_GET['action'] == "loginSignup") {
		
		$error = "" ;
		
		if (!$_POST['email']) {
			
			$error = "An Email Address is Required.";
				
		} else if (!$_POST['password']) {
			
			$error = "A Password is Required.";
			
		} else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false ) {
			
			$error = "Please Enter a Valid Email Address.";
		}
		
		if ($error != "") {
			
			echo($error);
			exit();
		}
		
		if ($_POST['loginActive'] == "0") {
			
			$query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($con, $_POST['email'])."' LIMIT 1";
			
			$result = mysqli_query($con, $query);
			
			if (mysqli_num_rows($result) > 0 ) {
				
				$error = "That Email Address is Already Taken.";
			}
			else
			{
				$query = "INSERT INTO users (`email`, `password`) VALUES ('". mysqli_real_escape_string($con, $_POST['email'])."','". mysqli_real_escape_string($con, $_POST['password'])."')";
				
				if (mysqli_query($con, $query)) {
					
					$_SESSION['id'] = mysqli_insert_id($con);
					
					//before starting Session
					//$query = "UPDATE users SET password = '". md5(md5(mysqli_insert_id($con)).$_POST['password']) ."' WHERE id = ".mysqli_insert_id($con)." LIMIT 1";
					
					//after starting Session
					$query = "UPDATE users SET password = '". md5(md5($_SESSION['id']).$_POST['password']) ."' WHERE id = ".$_SESSION['id']." LIMIT 1";
					mysqli_query($con, $query);
					
					echo("1");
					
					
					
				}
				else {
					
					$error = "Couldn't create user - please try again later";
				}
			}
		}
		else 
		{
			$query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($con, $_POST['email'])."' LIMIT 1";
			
			$result = mysqli_query($con, $query);
			
			$row = mysqli_fetch_assoc($result) ;
				
				if ($row['password'] == md5(md5($row['id']).$_POST['password'])) {
					
					echo(1);
					
					$_SESSION['id'] = $row['id'];
				}
				else
				{
					$error = "Could not find username/password combination. Please try again.";
				}
			}
		
		
		if ($error != "") {
			
			echo($error);
			exit();
		}
		
	}

	if($_GET['action'] == "toggleFollow"){
		
		$query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($con, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($con, $_POST['userId'])." LIMIT 1";
			
			$result = mysqli_query($con, $query);
			
			if (mysqli_num_rows($result) > 0 ) {
				
				$row = mysqli_fetch_assoc($result) ;
				
				mysqli_query($con, "DELETE FROM isFollowing WHERE id = ".mysqli_real_escape_string($con, $row['id'])." LIMIT 1");
				
				echo(1);
				
		     } else {
				
				mysqli_query($con, "INSERT INTO isFollowing (`follower`,`isFollowing`) VALUES (".mysqli_real_escape_string($con, $_SESSION['id']).", ".mysqli_real_escape_string($con, $_POST['userId']).")");
				
				echo(2);
				
			}
	
	}

	if($_GET['action'] == "postTweet") {
		
		if (!$_POST['tweetContent']) {
					
			echo("Your Tweet is Empty!");
					
		} else if (strlen($_POST['tweetContent']) > 140) {
			
			echo("Your Tweet is too Long!");
			
		} else {
			
			mysqli_query($con, "INSERT INTO tweets (`tweet`,`userid`,`datetime`) VALUES ('".mysqli_real_escape_string($con, $_POST['tweetContent'])."', ".mysqli_real_escape_string($con, $_SESSION['id']).", NOW())");
			
			echo(1);
			
		}
		
	}

?>




