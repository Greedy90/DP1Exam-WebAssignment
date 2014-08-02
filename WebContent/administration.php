<?php
	session_start();

	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
		session_unset();
		session_destroy();
		header("Location: index.php");
	}
	$_SESSION['LAST_ACTIVITY'] = time();
	
	if(isset($_SESSION['CurrentUser']) && isset($_SESSION['UserID']) && $_SESSION['UserID'] != 1)
		header("Location: index.php");
		
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - Administration - Home Page</title>
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
						<li><b>Admin Menu</b></li>
						<li><i>Home</i></li>
						<li><a href="adm_travel_trip.php">Travellers' trains list</a></li>
						<li><a href="adm_train_list.php">Trains list</a></li>
						<li><a href="adm_del_train.php">Delete train</a></li>
						<li><a href="adm_new_train.php">Add new train</a></li>
						<li><a href="logout.php" onclick="return quitSession();">Logout</a></li>
					</ul>
				</div>
			</div>
			<div class="central_part">
				<div class="borders">
					Welcome Admin!
				</div>
			</div>
		</div>
	</body>
</html>