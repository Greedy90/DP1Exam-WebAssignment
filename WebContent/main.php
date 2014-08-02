<?php
	session_start();
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
		session_unset();
		session_destroy();
		header("Location: index.php");
	}
	$_SESSION['LAST_ACTIVITY'] = time();
	
	if(isset($_SESSION['CurrentUser'])) {
		if(isset($_SESSION['UserID']) && $_SESSION['UserID'] == 1)
			header("Location: administration.php");
		else {
			$FirstName = $_SESSION['CurrentUser'];
			$UserID = $_SESSION['UserID'];
			
			$conn = @mysqli_connect("localhost", "s198855", "erscoman", "s198855");
			if(mysqli_connect_errno($conn)) {
				echo "Connection failed: ".mysqli_connect_error();
				exit();
			}
			
			$query = "SELECT Message FROM notifications WHERE ID_U = ".$UserID;
			$res = @mysqli_query($conn, $query);
			$numRows = mysqli_num_rows($res);
			if($numRows != 0) {
				$msg = "";
				while(($row = mysqli_fetch_array($res, MYSQL_ASSOC)) != NULL) {
					$msg .= ($row['Message']."\\n");
				}
				echo "<script type='text/javascript'>alert('".$msg."');</script>";
				mysqli_free_result($res);
				$query = "DELETE FROM notifications WHERE ID_U = ".$UserID;
				@mysqli_query($conn, $query);
				mysqli_close($conn);
			}
		}
	} else 
		header("Location: login.php");
		
	$pop = TRUE;
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - Home Page</title>
		<link rel="shortcut icon" href="img/favicon.ico">
		<script type="text/javascript"> <!--
			function checkCookies() {
				var check = false;
				document.cookie = "test=try";
				var cok = document.cookie;
				if(cok == "test=try") {
					cok = true;
					document.cookie += ";expires=Thu, 01-Jan-70 00:00:01 GMT";
				}
				return (cok);
			}

			function quitSession() {
			  if (confirm('Are you sure you want to logout?'))
			    return true;
			 	else
			    return false;
			}
		//--> </script>
		<noscript>
			<meta http-equiv="refresh" content="0; url=error_page.html"/>
		</noscript>
		<link rel="stylesheet" type="text/css" href="main_style.css">
	</head>
	<body>
		<script type="text/javascript"> <!--
			if(!checkCookies())
				window.location.assign("error_page.html");
		//--> </script>
		<div class="page">
			<div class="header">
				<div class="borders">
					TrainTO
				</div>
				<div id="pic">
					<img src="img/tgv.jpg" alt="train">
				</div>
			</div>
			<div class="navigator_bar">
				<div class="borders">
					<ul>
						<li><b>Menu</b></li>
						<li><i>Home</i></li>
						<li><a href="my_trip.php">My Trip</a></li>
						<li><a href="train_selection.php">Train Selection</a></li>
						<li><a href="logout.php" onclick="return quitSession();">Logout</a></li>
					</ul>
				</div>
			</div>
			<div class="central_part">
				<div class="borders">
					Welcome <?php echo $FirstName;?>!
				</div>
			</div>
		</div>
	</body>
</html>