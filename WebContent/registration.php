<?php
	session_start();
	
	$pop = TRUE;
	
	if(isset($_SESSION['CurrentUser'])) {
		if(isset($_SESSION['UserID']) && $_SESSION['UserID'] == 1)
			header("Location: administration.php");
		else
			header("Location: main.php");
	}
	
	if(isset($_REQUEST['cancel']))
		header("Location: index.php");
	
	if(isset($_REQUEST['next'])) {
		if($_REQUEST['firstTry'] == "")
			die("The password field is empty");
		if($_REQUEST['firstTry'] != $_REQUEST['secondTry'])
			die("The passwords you entered do not match, please go back and try again");
		if($_REQUEST['first'] == "")
			die("The first name field is empty");
		if($_REQUEST['last'] == "")
			die("The last name field is empty");
		if($_REQUEST['mail'] == "")
			die("The e-mail field is empty");
		if(!filter_var($_REQUEST['mail'], FILTER_VALIDATE_EMAIL))
			die("The e-mail is not valid");
			
		$conn = @mysqli_connect("localhost", "s198855", "erscoman", "s198855");
		if(mysqli_connect_errno($conn)) {
			echo "Connection failed: ".mysqli_connect_error();
			exit();
		}
		
		$firstName = htmlspecialchars(mysqli_real_escape_string($conn, $_REQUEST['first']));
		$lastName = htmlspecialchars(mysqli_real_escape_string($conn, $_REQUEST['last']));
		$mail = mysqli_real_escape_string($conn, $_REQUEST['mail']);
		$pass = md5(mysqli_real_escape_string($conn, $_REQUEST['firstTry']));
		
		$query = "SELECT COUNT(*) AS Count FROM users WHERE MailAddress = '".$mail."'";
		$res = @mysqli_query($conn, $query);
		$row = mysqli_fetch_array($res, MYSQL_ASSOC);
		
		if($row['Count'] == 0) {
		
			$query = "INSERT INTO users (FirstName,	LastName,	MailAddress, Password) VALUES ('$firstName', '$lastName', '$mail', '$pass')";
			
			@mysqli_query($conn, $query);
			
			$query = "SELECT LAST_INSERT_ID() AS ID";
			
			$res = @mysqli_query($conn, $query);
			$row = mysqli_fetch_array($res, MYSQL_ASSOC);
			
			$_SESSION['CurrentUser'] = $firstName;
			$_SESSION['UserID'] = $row['ID'];
			header("Location: main.php");
		} else
			echo "<script type='text/javascript'>alert('The mail that you have inserted is already used!');</script>";
		
		mysqli_free_result($res);
		mysqli_close($conn);
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - Registration</title>
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

			function checkFields() {
				if(document.input_new_account.firstTry.value != document.input_new_account.secondTry.value) {
					alert("The passwords you entered do not match, please go back and try again.");
					document.input_new_account.firstTry.value = "";
					document.input_new_account.secondTry.value = "";
					document.input_new_account.firstTry.focus();
				  return (false);
				}
	
				if(document.input_new_account.first.value == "") {
					alert("The first name field is empty.");
					document.input_new_account.first.focus();
					return(false);
				}
	
				if(document.input_new_account.last.value == "") {
					alert("The last name field is empty.");
					document.input_new_account.last.focus();
					return(false);
				}
	
				if(document.input_new_account.mail.value == "") {
					alert("The e-mail field is empty.");
					document.input_new_account.mail.focus();
					return(false);
				} else if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.input_new_account.mail.value)) {
					alert("The e-mail is not valid.");
					document.input_new_account.mail.value = ""
					document.input_new_account.mail.focus();
					return(false);
				}

				if(document.input_new_account.firstTry.value == "") {
					alert("The password field is empty.");
					document.input_new_account.firstTry.focus();
					return(false);
				}

				return(true);
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
				<div id="pop_up">
					Please, complete the following form
				</div>
				<div>
					<form action="registration.php" method="post" name="input_new_account" onsubmit="return checkFields()">
						<fieldset>
							<legend>New Account</legend>
							First Name: <input type="text" name="first" title="Insert here your first name"><br>
							Last Name: <input type="text" name="last" title="Insert here your last name"><br>
							Mail: <input type="text" name="mail" title="Insert here your email, this will be your username"><sup>this will be your username</sup><br>
							Password: <input type="password" name="firstTry" title="Please, insert here your password, composed by numbers and letters only"><sup>insert numbers and letters only</sup><br>
							Check Password: <input type="password" name="secondTry" title="Re-type your email for check"><br>
						</fieldset>
						<input type="submit" name="next" value="Create New User" title="Create your new user account and enter into TrainTO">
						<input type="reset" value="Clear" title="Reset the fields">
						<input type="button" name="cancel" value="Cancel" onclick="window.location='index.php'" title="Go back to the index page">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>