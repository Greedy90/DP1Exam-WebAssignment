<?php
	session_start();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - Logout</title>
		<link rel="shortcut icon" href="img/favicon.ico">
		<link rel="stylesheet" type="text/css" href="main_style.css">
		<meta http-equiv="refresh" content="5; url=index.php"/>
	</head>
	<body>
		<div class="page" align="center">
			<?php
				if(isset($_SESSION['CurrentUser']))
					echo "Goodbye ".html_entity_decode($_SESSION['CurrentUser'])."!";
				else
					echo "Goodbye!";
				
				session_unset();
				session_destroy();
			?>
			<br>You have logged out successfully.
			<p>Click <a href="index.php">here</a> if redirect is too slow.</p>
		</div>
	</body>
</html>