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
	
	$flag = FALSE;
	
	$conn = @mysqli_connect("localhost", "s198855", "erscoman", "s198855");
	if(mysqli_connect_errno($conn)) {
		echo "Connection failed: ".mysqli_connect_error();
		exit();
	}
	
	if(isset($_REQUEST['validate'])) {
		$query = "SELECT tra.TrainNumber AS trainNo,
										 s_a.Station AS depStat,
										 tra.DepartureTime AS depTime,
										 s_b.Station AS arrStat,
										 tra.ArrivalTime AS arrTime
							FROM trains AS tra,
									 trips AS tri,
									 stations AS s_a,
									 stations AS s_b
							WHERE tri.ID_U = ".$UserID." AND
										tri.ID_T = tra.ID AND
										tra.IdDepartureStation = s_a.ID AND
										tra.IdArrivalStation = s_b.ID
							ORDER BY tra.DepartureTime";
		
		$res = @mysqli_query($conn, $query);
		
		$flag = FALSE;
		$flag_check = TRUE;

		while(($row = mysqli_fetch_array($res, MYSQL_ASSOC)) != NULL) {
			if($flag == FALSE) {
				$prec = $row['arrStat'];
				$flag = TRUE;
				continue;
			}
			if($prec == $row['depStat'])
				$prec = $row['arrStat'];
			else {
				$flag_check = FALSE;
				echo "<script type='text/javascript'>alert('Your journey cannot be validated');</script>";
				break;
			}
		}
		
		if($flag_check)
			echo "<script type='text/javascript'>alert('Your journey is validated');</script>";
	}
	
	if(isset($_REQUEST['delete_selected'])) {
		if(isset($_REQUEST['train_delete'])) {
			$query = "DELETE FROM trips WHERE ";
			
			foreach ($_REQUEST['train_delete'] as $train_id)
				$query .= "ID_T = ".$train_id." OR ";
			$query .= "0";
			
			@mysqli_query($conn, $query);
			echo "<script type='text/javascript'>alert('The selected train is removed from your train list');</script>";
		} else
			echo "<script type='text/javascript'>alert('You have selected no trains!');</script>";
	}
	
	if(isset($_REQUEST['validate'])) {
		
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - My Trip</title>
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
						<li><a href="main.php">Home</a></li>
						<li><i>My Trip</i></li>
						<li><a href="train_selection.php">Train Selection</a></li>
						<li><a href="logout.php" onclick="return quitSession();">Logout</a></li>
					</ul>
				</div>
			</div>
			<div class="central_part">
				<div class="borders">
					My Trip
				</div>
				<div id="train_list">
					<form name="output_train_list" action="my_trip.php" method="post">
						<table style="font-family:Tahoma;font-variant:small-caps;font-size:14px;line-height:20px;width:100%">
							<tr>
								<th>Train Number</th><th>Departure Station</th><th>Departure Time</th><th>Arrival Station</th><th>Arrival Time</th><th>Delete</th>
							</tr>
							<?php						
								$query = "SELECT tra.ID AS trainID,
																 tra.TrainNumber AS trainNo,
																 s_a.Station AS depStat,
																 tra.DepartureTime AS depTime,
																 s_b.Station AS arrStat,
																 tra.ArrivalTime AS arrTime
													FROM trains AS tra,
															 trips AS tri,
															 stations AS s_a,
															 stations AS s_b
													WHERE tri.ID_U = ".$UserID." AND
																tri.ID_T = tra.ID AND
																tra.IdDepartureStation = s_a.ID AND
																tra.IdArrivalStation = s_b.ID
													ORDER BY tra.DepartureTime";
		
								$res = @mysqli_query($conn, $query);
								
								$flag = FALSE;
								
								$tot_time = strtotime('00:00:00');
									
								while(($row = mysqli_fetch_array($res, MYSQL_ASSOC)) != NULL) {
									$flag = TRUE;
									$time_train = strtotime($row['arrTime']) - strtotime($row['depTime']);
									$tot_time += $time_train;
									echo "<tr>
													<td>".$row['trainNo']."</td>
												  <td>".html_entity_decode($row['depStat'])."</td>
												  <td>".$row['depTime']."</td>
												  <td>".html_entity_decode($row['arrStat'])."</td>
												  <td>".$row['arrTime']."</td>
												  <td><input type='checkbox' name='train_delete[]' value='".$row['trainID']."'></td>
												</tr>";
								}
								
								echo "<tr><td colspan='100%'>Total time spent on train: ".date('H:i',$tot_time)."</td></tr>";
								
								if($flag != TRUE)
									echo "<tr><td colspan='100%'>Your train list is empty.</td></tr>";
								mysqli_free_result($res);
							?>
						</table>
						<fieldset>
							<?php if($flag == TRUE):?>
								<input type="submit" name="validate" value="Validate" title="Check if the arrival station of a train is equal to the departure station of the next one">
							<?php endif; ?>
							<input type="button" name="confirm" value="Confirm" onclick="window.location='main.php'" title="Confirm your trip and go back to the main page">
							<input type="button" name="add_new_train" value="Add new train" onclick="window.location='train_selection.php'" title="Add new train to your trip">
							<?php if($flag == TRUE):?>
								<input type="submit" name="delete_selected" value="Delete selected train(s)" title="Delete the selected train">
							<?php endif; ?>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>