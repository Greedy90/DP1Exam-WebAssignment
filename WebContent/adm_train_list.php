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
	
	$conn = @mysqli_connect("localhost", "s198855", "erscoman", "s198855");
	if(mysqli_connect_errno($conn)) {
		echo "Connection failed: ".mysqli_connect_error();
		exit();
	}
		
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - Administration - Train List</title>
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
						<li><a href="adm_travel_trip.php">Travellers' trains list</a></li>
						<li><i>Trains list</i></li>
						<li><a href="adm_del_train.php">Delete train</a></li>
						<li><a href="adm_new_train.php">Add new train</a></li>
						<li><a href="logout.php" onclick="return quitSession();">Logout</a></li>
					</ul>
				</div>
			</div>
			<div class="central_part">
				<div class="borders">
					Trains list
				</div>
				<div id="train_list">
					<table style="font-family:Tahoma;font-variant:small-caps;font-size:14px;line-height:20px;width:100%;">
						<tr>
							<th>Train ID</th><th>Train Number</th><th>Departure Station</th><th>Departure Time</th><th>Arrival Station</th><th>Arrival Time</th>
						</tr>
						<?php
							$query = "SELECT t.ID AS TrainID,
															 t.TrainNumber AS TrainNo,
															 s_a.Station AS DepStat,
															 s_b.Station AS ArrStat,
															 t.DepartureTime AS DepTime,
															 t.ArrivalTime AS ArrTime
												FROM trains AS t,
														 stations AS s_a,
														 stations AS s_b
												WHERE t.IdDepartureStation = s_a.ID AND
															t.IdArrivalStation = s_b.ID
												ORDER BY t.TrainNumber";

							$res = @mysqli_query($conn, $query);
							
							while(($row = mysqli_fetch_array($res, MYSQL_ASSOC)) != NULL) {
								echo "<tr>
												<td>".$row['TrainID']."</td>
												<td>".$row['TrainNo']."</td>
											  <td>".html_entity_decode($row['DepStat'])."</td>
											  <td>".$row['DepTime']."</td>
											  <td>".html_entity_decode($row['ArrStat'])."</td>
											  <td>".$row['ArrTime']."</td>
											</tr>";
							}
							mysqli_free_result($res);
							mysqli_close($conn);
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>