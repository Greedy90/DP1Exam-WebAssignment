<?php
	session_start();
	
	if(!isset($_SERVER['HTTPS'])) {
		header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		exit;
	}

	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
		session_unset();
		session_destroy();
		header("Location: index.php");
	}
	$_SESSION['LAST_ACTIVITY'] = time();
	
	$pop = TRUE;
	
	if(isset($_SESSION['CurrentUser'])) {
		if(isset($_SESSION['UserID']) && $_SESSION['UserID'] == 1)
			header("Location: administration.php");
		else
			header("Location: main.php");
	}

	if(isset($_REQUEST['cancel']))
		header("Location: index.php");
	
	if(isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
		
		$conn = @mysqli_connect("localhost", "s198855", "erscoman", "s198855");
		if(mysqli_connect_errno($conn)) {
			echo "Connection failed: ".mysqli_connect_error();
			exit();
		}
		
		$username = htmlspecialchars(mysqli_real_escape_string($conn, $_REQUEST['username']));
		$password = htmlspecialchars(mysqli_real_escape_string($conn, (md5($_REQUEST['password']))));
		
		$query = "SELECT COUNT(*) AS Count, FirstName AS Username, ID AS UserID FROM users WHERE MailAddress = BINARY('".$username."') AND Password = '".$password."'";
		$res = @mysqli_query($conn, $query);
		
		$row = mysqli_fetch_array($res, MYSQL_ASSOC);
		if($row == NULL)
			$pop = FALSE;
		else {
			if($row['Count'] != 0) {
				$_SESSION['CurrentUser'] = $row['Username'];
				$_SESSION['UserID'] = $row['UserID'];
				if($row['UserID'] == 1)
					header("Location: administration.php");
				else
					header("Location: main.php");
			} else
				$pop = FALSE;
		}
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - Login</title>
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
						<li><a href="login.php">Login</a></li>
						<li><a href="registration.php">New User</a></li>
					</ul>
				</div>
			</div>
			<div class="central_part">
				<div class="borders">
					<?php if($pop == TRUE): ?>
						<div id="pop_up">
							Please, enter valid username and password
						</div>
					<?php else: ?>
						<div id="pop_up">
							Invalid username or password
						</div>
					<?php endif?>
					<div>
						<form action="login.php" method="post">
							<fieldset>
								<legend>Account</legend>
								Mail: <input type="text" name="username" title="Enter your e-mail address here"><br>
								Password: <input type="password" name="password" title="Enter your password here"><br>
							</fieldset>
							<input class="tooltip" type="submit" value="Login">
							<input type="reset" value="Clear" title="Cancel the fields">
							<input type="button" name="cancel" value="Cancel" onclick="window.location='index.php'" title="Go back to index">
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>