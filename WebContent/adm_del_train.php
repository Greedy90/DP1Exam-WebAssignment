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
	
	if(isset($_REQUEST['delete'])) {
		if($_REQUEST['TrainNo'] == "")
			die("The train number field is empty");
		if(!is_numeric($_REQUEST['TrainNo']))
			die("Train number must be a number");
		if($_REQUEST['DepStat'] == "")
			die("The departure station field is empty");
		if($_REQUEST['ArrStat'] == "")
			die("The arrival station field is empty");
		if($_REQUEST['DepStat'] == $_REQUEST['ArrStat'])
			die("The departure station is equal to the arrival station");
		
		$conn = @mysqli_connect("localhost", "s198855", "erscoman", "s198855");
		if(mysqli_connect_errno($conn)) {
			echo "Connection failed: ".mysqli_connect_error();
			exit();
		}

		$TrainNo = mysqli_real_escape_string($conn, $_REQUEST['TrainNo']);
		$DepStat = htmlspecialchars(mysqli_real_escape_string($conn, $_REQUEST['DepStat']));
		$ArrStat = htmlspecialchars(mysqli_real_escape_string($conn, $_REQUEST['ArrStat']));
		
		$query = "SELECT t.ID AS TrainID,
										 t.TrainNumber AS TrainNo,
										 s_a.Station AS DepStat,
										 s_b.Station AS ArrStat,
										 DepartureTime AS DepTime,
										 ArrivalTime AS ArrTime
							FROM trains AS t,
									 stations AS s_a,
									 stations AS s_b
							WHERE t.TrainNumber = ".$TrainNo." AND
										t.IdDepartureStation = s_a.ID AND
										t.IdArrivalStation = s_b.ID AND
										s_a.Station = '".$DepStat."' AND
										s_b.Station = '".$ArrStat."'";
		
		$res = @mysqli_query($conn, $query);
		$numRows = mysqli_num_rows($res);
		if($numRows == 0) {
			mysqli_free_result($res);
			mysqli_close($conn);
			echo "<script type='text/javascript'>alert('There is no train with this parameters!');</script>";
		} else {
			$row = mysqli_fetch_array($res, MYSQL_ASSOC);
			$TrainID = $row['TrainID'];
			$TrainNo = $row['TrainNo'];
			$DepStat = $row['DepStat'];
			$ArrStat = $row['ArrStat'];
			$DepTime = $row['DepTime'];
			mysqli_free_result($res);
			$query = "SELECT ID_U AS UserID
								FROM trips
								WHERE ID_T = ".$TrainID;
			$res = @mysqli_query($conn, $query);
			$numRows = mysqli_num_rows($res);
			if($numRows == 0) { // no user is involved
				mysqli_free_result($res);
				$query = "DELETE FROM trains WHERE ID = ".$TrainID;
				@mysqli_query($conn, $query);
				mysqli_close($conn);
			} else { // one or more users are involved
				$i = 0;
				while(($row = mysqli_fetch_array($res, MYSQL_ASSOC)) != NULL) {
					$UserID[$i] = $row['UserID'];
					$i++;
				}
				mysqli_free_result($res);
				foreach ($UserID as $id) {
					$query = "INSERT INTO notifications (ID_U, Message) VALUES
										(".$id.", 'WARNING - train number ".$TrainNo." at ".$DepTime." from ".$DepStat." to ".$ArrStat." has been cancelled')";
					@mysqli_query($conn, $query);
				}
				$query = "DELETE FROM trains WHERE ID = ".$TrainID;
				@mysqli_query($conn, $query);
				mysqli_close($conn);
			}
		}
	}	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>TrainTO - Administration - New Train</title>
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

			function checkDelTrain() {
				if (document.input_del_train.TrainNo.value === "") {
					alert("The train number field is empty!");
					document.input_del_train.TrainNo.focus();
	        return (false);
				}

				if (isNaN(document.input_del_train.TrainNo.value)) {
					alert("The train number field must contains numbers only!");
					document.input_del_train.TrainNo.value = 0;
					return (false);
				}

				if (document.input_del_train.DepStat.value === "") {
	        alert("The departure station field is empty!");
	        document.input_del_train.DepStat.focus();
	        return (false);
				}

				if (document.input_del_train.DepStat.value === document.input_del_train.ArrStat.value) {
					alert("Departure station cannot be equal to the arrival station!");
					return (false);
				}

				if (document.input_del_train.ArrStat.value === "") {
	        alert("The arrival station field is empty!");
	        document.input_del_train.ArrStat.focus();
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
						<li><b>Admin Menu</b></li>
						<li><a href="administration.php">Home</a></li>
						<li><a href="adm_travel_trip.php">Travellers' trains list</a></li>
						<li><a href="adm_train_list.php">Trains list</a></li>
						<li><i>Delete train</i></li>
						<li><a href="adm_new_train.php">Add new train</a></li>
						<li><a href="logout.php" onclick="return quitSession();">Logout</a></li>
					</ul>
				</div>
			</div>
			<div class="central_part">
				<div class="borders">
					Remove train
				</div>
				<div id="del_train_form">
					<form action="adm_del_train.php" method="post" name="input_del_train" onsubmit="return checkDelTrain()">
						<fieldset>
							<legend>Insert here the details of the train:</legend>
							Train Number: <input type="text" name="TrainNo" title="Insert here the number of the train"><br>
							Departure Station: <input type="text" name="DepStat" title="Insert the departure station of the train">
							ArrivalStation: <input type="text" name="ArrStat" title="Insert the arrival station of the train">
						</fieldset>
						<input type="submit" name="delete" value="Delete" title="Delete the train">
						<input type="reset" value="Clear" title="Reset the fields">
						<input type="button" name="cancel" value="Cancel" onclick="window.location='administration.php'" title="Go back to the main page">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>