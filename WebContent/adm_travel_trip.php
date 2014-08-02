<?php
	session_start();

	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
		session_unset();
		session_destroy();
		header("Location: index.php");
	}
	$_SESSION['LAST_ACTIVITY'] = time();
	
	if(isset($_SESSION['CurrentUser']) && isset($_SESSION['UserID']) && $_SESSION['UserID'] != 1)
		header("Location: logout.php");
	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - Administration - Travellers' Trips</title>
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
						<li><a href="administration.php">Home</a></li>
						<li><i>Travellers' trains list</i></li>
						<li><a href="adm_train_list.php">Trains list</a></li>
						<li><a href="adm_del_train.php">Delete train</a></li>
						<li><a href="adm_new_train.php">Add new train</a></li>
						<li><a href="logout.php" onclick="return quitSession();">Logout</a></li>
					</ul>
				</div>
			</div>
			<div class="central_part">
				<div class="borders">
					Travellers' trains list
				</div>
				<div id="train_list">
					<?php
						if(isset($_REQUEST['search'])) {
							if($_REQUEST['username'] == "")
								die("Username field is empty");
							echo "Train trip for user ".html_entity_decode($_REQUEST['username']);
							echo "<table style='font-family:Tahoma;font-variant:small-caps;font-size:14px;line-height:20px;width:100%;'>";
							echo "<tr>
											<th>Train ID</th><th>Train Number</th><th>Departure Station</th><th>Departure Time</th><th>Arrival Station</th><th>Arrival Time</th>
										</tr>";
						
							$conn = @mysqli_connect("localhost", "s198855", "erscoman", "s198855");
							if(mysqli_connect_errno($conn)) {
								echo "Connection failed: ".mysqli_connect_error();
								exit();
							}
							
							$mail = htmlspecialchars(mysqli_real_escape_string($conn, $_REQUEST['username']));
							
							$query = "SELECT tra.ID AS TrainID,
															 tra.TrainNumber AS TrainNo,
															 s_a.Station AS DepStat,
															 s_b.Station AS ArrStat,
															 tra.DepartureTime AS DepTime,
															 tra.ArrivalTime AS ArrTime
												FROM trains AS tra,
														 trips AS tri,
														 users AS u,
														 stations AS s_a,
														 stations AS s_b
												WHERE tra.IdDepartureStation = s_a.ID AND
															tra.IdArrivalStation = s_b.ID AND
															tri.ID_T = tra.ID AND
															u.MailAddress = '".$mail."' AND
															tri.ID_U = u.ID
												ORDER BY tra.DepartureTime";
							
							$res = @mysqli_query($conn, $query);
							
							if(mysqli_num_rows($res) != 0) {
								while(($row = mysqli_fetch_array($res, MYSQL_ASSOC)) != NULL) {
									echo "<tr>
													<td>".$row['TrainID']."</td>
													<td>".$row['TrainNo']."</td>
												  <td>".$row['DepStat']."</td>
												  <td>".$row['DepTime']."</td>
												  <td>".$row['ArrStat']."</td>
												  <td>".$row['ArrTime']."</td>
												</tr>";
								}
								mysqli_free_result($res);
								mysqli_close($conn);
							} else
								echo "<tr><td colspan='100%'>The selected user has no train reservations</td></tr>";
						}
					?>
					</table>
				</div>
				<div id="user_select">
					<form name="input_user_search" action="adm_travel_trip.php" method="post">
						<fieldset>
							<legend>Insert username:</legend>
							<input type="text" name="username" title="Insert here the username"><br>
							<input type="submit" name="search" value="Search" title="Show the trip">
							<input type="reset" name="reset" value="Reset" title="Clear the username field">
							<input type="button" name="cancel" value="Cancel" onclick="window.location='administration.php'" title="Go back to the main page">
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>