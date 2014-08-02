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
		}
	} else
		header("Location: login.php");
	
	$conn = @mysqli_connect("localhost", "s198855", "erscoman", "s198855");
	if(mysqli_connect_errno($conn)) {
		echo "Connection failed: ".mysqli_connect_error();
		exit();
	}
	
	$flag = FALSE;
	
	if(isset($_REQUEST['buy_travel'])) {
		
		$query = "SELECT COUNT(*) AS Count
							FROM trains AS tra,
									 trips AS tri
							WHERE tri.ID_U = ".$_SESSION['UserID']." AND
										tra.ID = tri.ID_T AND
										((tra.DepartureTime < '".$_REQUEST['depTime'.$_REQUEST['train_select']]."' AND
								 	 	  tra.ArrivalTime > '".$_REQUEST['arrTime'.$_REQUEST['train_select']]."') OR
								 		 (tra.DepartureTime > '".$_REQUEST['depTime'.$_REQUEST['train_select']]."' AND
								  		tra.DepartureTime < '".$_REQUEST['arrTime'.$_REQUEST['train_select']]."') OR
								 		 (tra.DepartureTime > '".$_REQUEST['depTime'.$_REQUEST['train_select']]."' AND
								  		tra.ArrivalTime < '".$_REQUEST['arrTime'.$_REQUEST['train_select']]."') OR
								 		 (tra.ArrivalTime > '".$_REQUEST['depTime'.$_REQUEST['train_select']]."' AND
								  		tra.ArrivalTime < '".$_REQUEST['arrTime'.$_REQUEST['train_select']]."'))";
		
		$res = @mysqli_query($conn, $query);
		$row = mysqli_fetch_array($res, MYSQL_ASSOC);
		if($row['Count'] == 0) {
			$query = "INSERT INTO trips (ID_T, ID_U) VALUES (".$_REQUEST['train_select'].", ".$UserID.")";
			@mysqli_query($conn, $query);
			mysqli_close($conn);
			header("Location: my_trip.php");
		} else
			echo "<script type='text/javascript'>alert('This train is incompatible with your train list, please select another train.');</script>";
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - Train Selection</title>
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

			function checkStations() {
				if((document.input_train_search.depStat.value == document.input_train_search.arrStat.value) &&
					 (document.input_train_search.depStat.value != "" || document.input_train_search.arrStat.value != "")) {
					alert("The departure station and the arrival station cannot be the same!");
					document.input_train_search.arrStat.value = "";
					return (false);
				} 

				if(document.input_train_search.depStat.value == "") {
					alert("The departure station cannot be empty!");
					return (false);
				} 

				if(document.input_train_search.arrStat.value == "") {
					alert("The arrival station cannot be empty!");
					return (false);
				} 

				return (true);
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
						<li><a href="main.php">Home</a></li>
						<li><a href="my_trip.php">My Trip</a></li>
						<li><i>Train Selection</i></li>
						<li><a href="logout.php" onclick="return quitSession();">Logout</a></li>
					</ul>
				</div>
			</div>
			<div class="central_part">
				<div class="borders">
					Train Selection
				</div>
				<?php if(isset($_REQUEST['search'])): ?>
					<?php
						if(($_REQUEST['depStat'] == $_REQUEST['arrStat']) && ($_REQUEST['depStat'] != "" || $_REQUEST['arrStat'] != ""))
							die("The departure station and the arrival station cannot be the same");
						if($_REQUEST['depStat'] == "")
							die("The departure station cannot be empty");
						if($_REQUEST['arrStat'] == "")
							die("The arrival station cannot be empty");
					?>	
					<div id="train_list">
						<form name="input_train_list" action="train_selection.php" method="post">
							<table style="font-family:Tahoma;font-variant:small-caps;font-size:14px;line-height:20px;width:100%;">
								<tr>
									<th>Train Number</th><th>Departure Station</th><th>Departure Time</th><th>Arrival Station</th><th>Arrival Time</th><th>Select</th>
								</tr>
								<?php
									$query = "SELECT t.ID AS trainID,
																	 t.TrainNumber AS trainNo,
																	 s_a.Station AS depStat,
													  			 s_b.Station AS arrStat,
													 				 t.DepartureTime AS depTime,
													 				 t.ArrivalTime AS arrTime
											     	FROM trains AS t,
												   			 stations AS s_a,
												    	   stations AS s_b
											  		WHERE t.IdDepartureStation = s_a.ID AND
																	t.IdArrivalStation = s_b.ID AND
																	s_a.ID = '".$_REQUEST['depStat']."' AND
																	s_b.ID = '".$_REQUEST['arrStat']."'";
									if($_REQUEST['depTime'] != "") {
										if($_REQUEST['depTime'] == "mourning")
											$query .= " AND DepartureTime >= '07:00:00' AND DepartureTime < '12:00:00'";
										elseif($_REQUEST['depTime'] == "afternoon")
											$query .= " AND DepartureTime >= '12:00:00' AND DepartureTime < '17:00:00'";
										elseif($_REQUEST['depTime'] == "evening")
											$query .= " AND DepartureTime >= '17:00:00' AND DepartureTime < '23:00:00'";
									}
									$query .= " ORDER BY t.DepartureTime";
									
									$res = @mysqli_query($conn, $query);
									
									while(($row = mysqli_fetch_array($res, MYSQL_ASSOC)) != NULL) {
										$flag = TRUE;
										echo "<tr>
														<td>".$row['trainNo']."</td>
													  <td>".$row['depStat']."</td>
													  <td>".html_entity_decode($row['depTime'])."</td>
													  <input type='hidden' name='depTime".$row['trainID']."' value='".$row['depTime']."'>
													  <td>".html_entity_decode($row['arrStat'])."</td>
													  <td>".$row['arrTime']."</td>
													  <input type='hidden' name='arrTime".$row['trainID']."' value='".$row['arrTime']."'>
													  <td><input type='radio' name='train_select' value='".$row['trainID']."'></td>
													</tr>";
									}
									
									if($flag != TRUE)
										echo "<tr><td colspan='100%'>No match for your search.</td></tr>";
									else
										echo "<tr><td colspan='100%'><input type='submit' name='buy_travel' value='Buy Travel'></td></tr>";
									mysqli_free_result($res);
								?>
							</table>
						</form>
					</div>
				<?php endif; ?>
				<div id="train_select">
					<form name="input_train_search" action="train_selection.php" method="post" onsubmit="return checkStations()">
						<fieldset>
							<legend>Select the preferred options:</legend>
							Departure Station: 
							<select name="depStat" title="Select the departure station here">
								<option value=""> Select station </option>
							  <?php
								  $query = "SELECT ID, Station FROM stations ORDER BY Station ASC";
								  
								  $res = @mysqli_query($conn, $query);
								  
								  while(($row = mysqli_fetch_array($res, MYSQL_ASSOC)) != NULL)
								  	echo "<option value=".$row['ID'].">".html_entity_decode($row['Station'])."</option>";
								  
								  mysqli_free_result($res);
							  ?>
							</select>
							Arrival Station: 
							<select name="arrStat" title="Select the arrival station here">
								<option value=""> Select station </option>
							  <?php
								  $query = "SELECT ID, Station FROM stations ORDER BY Station ASC";
								  
								  $res = @mysqli_query($conn, $query);
								  
								  while(($row = mysqli_fetch_array($res, MYSQL_ASSOC)) != NULL)
								  	echo "<option value=".$row['ID'].">".html_entity_decode($row['Station'])."</option>";
								  
								  mysqli_free_result($res);
								  mysqli_close($conn);
							  ?>
							</select>
							<br>Departure Time: 
							<select name="depTime" title="Select the time range here">
								<option value="no_pref">No preference</option>
							  <option value="mourning">07:00 - 12:00</option>
							  <option value="afternoon">12:00 - 17:00</option>
							  <option value="evening">17:00 - 23:00</option>
							</select>
						</fieldset>
						<input type="submit" name="search" value="Search" title="Search the train">
						<input type="reset" name="reset" value="Clear" title="Reset the fields">
						<input type="button" name="cancel" value="Cancel" onclick="window.location='main.php'" title="Go back to the main page">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>