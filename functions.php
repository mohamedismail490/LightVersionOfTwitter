<?php

	session_start();
	setcookie("customerId", "1234", time() + 60 * 60 * 24);

	$con = mysqli_connect("localhost","root","","twitter");

	if (mysqli_connect_error()) {
		
		print_r(mysqli_connect_error());
		exit();
	}

	if (isset($_GET['function']) == "logout") {
        
        session_unset();
	}

		function time_since($since) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , 'sec')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;
    }

	function displayTweets($type) {
        
        global $con;
        
        if ($type == 'public') {
            
            $whereClause = "";
                
        } else if (($type == 'isFollowing') && (isset($_SESSION['id']))) {
			
			$query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($con, $_SESSION['id']);
			
			$result = mysqli_query($con, $query);
			
			$whereClause = "";
			
				while ($row = mysqli_fetch_assoc($result)) {
					
					if ($whereClause == "") {
						
						$whereClause = "WHERE";
						
					}
					else {
						
						$whereClause .= " OR";
						
					}
					
					$whereClause .= " userid = ".$row['isFollowing'];
					
				}
			
		} else if (($type == 'yourtweets') && (isset($_SESSION['id']))) {
			
			$whereClause = "WHERE userid = ".mysqli_real_escape_string($con, $_SESSION['id']);
			
		} else if ($type == 'search') {
			
			echo('<p>Showing results for "'.mysqli_real_escape_string($con, $_GET['q']).'":</p>');
			
			$whereClause = "WHERE tweet LIKE '%".mysqli_real_escape_string($con, $_GET['q'])."%'";
			
		} else if (is_numeric($type)) {
			
			$userQuery =  "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($con, $type)." LIMIT 1";
				   
		    $userQueryResult = mysqli_query($con, $userQuery);
				
			$user = mysqli_fetch_assoc($userQueryResult);
			
			echo("<h2>".mysqli_real_escape_string($con, $user['email'])."'s Tweets</h2>");
			
			$whereClause = "WHERE userid = ".mysqli_real_escape_string($con, $type);
			
		}

		if (isset($_SESSION['id'])){

		$query = "SELECT * FROM tweets ".$whereClause." ORDER BY `datetime` DESC LIMIT 10";
		
		$result = mysqli_query($con, $query);
        }
		
//		if (mysqli_num_rows($result) == 0) {
        if ((!isset($_SESSION['id'])) || (mysqli_num_rows($result) == 0)) {
			
			echo("There are no Tweets to Display.");
		
		} else {
			
			while ($row = mysqli_fetch_assoc($result)) {
			
			$userQuery =  "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($con, $row['userid'])." LIMIT 1";
				   
		    $userQueryResult = mysqli_query($con, $userQuery);
				
			$user = mysqli_fetch_assoc($userQueryResult);
				
				echo("<div class='tweet'><p><a href='?page=publicprofiles&userid=".$user['id']."'>".$user['email']."</a> <span class='time'>".time_since(time() - strtotime($row['datetime']))." ago</span>:</p>");
				
				echo("<p>".$row['tweet']."</p>");
				
				echo("<p><a class='toggleFollow' data-userId='".$row['userid']."'>");


				if (isset($_SESSION['id'])) {

                    $isFollowingQuery = "SELECT * FROM isFollowing WHERE follower = " . mysqli_real_escape_string($con, $_SESSION['id']) . " AND isFollowing = " . mysqli_real_escape_string($con, $row['userid']) . " LIMIT 1";

                    $isFollowingQueryResult = mysqli_query($con, $isFollowingQuery);

                    if (mysqli_num_rows($isFollowingQueryResult) > 0) {

                        echo("Unfollow");

                    } else {

                        echo("Follow");

                    }
                }
				
				echo("</a></p></div>");
			
			}
		}
	}

	function displaySearch() {
		
		echo('<form class="form-inline">
			  <div class="form-group mx-sm-2 mb-2">
			    <input type="hidden" name="page" value="search">
				<input type="text" name="q" class="form-control" id="search" placeholder="search">
			  </div>
			  <button class="btn btn-primary mx-sm-2 mb-2">Search Tweets</button>
			</form>');
		
	}

	function displayTweetBox() {
		
		if (isset($_SESSION['id']) > 0) {
			
			echo('<div id="tweetSuccess" class="alert alert-success">Your Tweet was posted Successfully.</div>
			<div id="tweetFail" class="alert alert-danger"></div>
			<div class="form">
			  <div class="form-group mx-sm-2 mb-2">
				<textarea class="form-control" id="tweetContent"></textarea>
			  </div>
			  <button id="postTweetButton" class="btn btn-primary mx-sm-2 mb-2">Post Tweets</button>
			</div>');
		}
		
	}

	function displayUsers() {
		
		global $con ;
		
		$query = "SELECT * FROM users LIMIT 10";
		
		$result = mysqli_query($con, $query);
		
			while ($row = mysqli_fetch_assoc($result)) {
				
				echo("<p><a href='?page=publicprofiles&userid=".$row['id']."'>".$row['email']."</a></p>");
				
			}
		
	}

	

?>
















