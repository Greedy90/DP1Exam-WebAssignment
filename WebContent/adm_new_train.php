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
	
	if(isset($_REQUEST['insert'])) {
		
		if($_REQUEST['TrainNo'] == "")
			die("Train number field is empty");
		if($_REQUEST['TrainNo'] > 9999)
			die("Train number must be a value between 0 and 9999");
		if(!is_numeric($_REQUEST['TrainNo']))
			die("Train number must be a number");
		if($_REQUEST['DepStat'] == "")
			die("Departure station field is empty");
		if($_REQUEST['ArrStat'] == "")
			die("Arrival station field is empty");
		if($_REQUEST['DepStat'] == $_REQUEST['ArrStat'])
			die("Departure station is equal to the arrival station");
		if($_REQUEST['DepTimeHour'] > $_REQUEST['ArrTimeHour'])
			die("The arrival time is lower than the departure time");
		if($_REQUEST['DepTimeHour'] == $_REQUEST['ArrTimeHour'] && $_REQUEST['DepTimeMin'] == $_REQUEST['ArrTimeMin'])
			die("The arrival time is lower than the departure time");
		
		$conn = @mysqli_connect("localhost", "s198855", "erscoman", "s198855");
		if(mysqli_connect_errno($conn)) {
			echo "Connection failed: ".mysqli_connect_error();
			exit();
		}
		
		$TrainNo = mysqli_real_escape_string($conn, $_REQUEST['TrainNo']);
		$DepStat = htmlspecialchars(mysqli_real_escape_string($conn, $_REQUEST['DepStat']));
		$DepTimeHour = $_REQUEST['DepTimeHour'];
		$DepTimeMin = $_REQUEST['DepTimeMin'];
		$ArrStat = htmlspecialchars(mysqli_real_escape_string($conn, $_REQUEST['ArrStat']));
		$ArrTimeHour = $_REQUEST['ArrTimeHour'];
		$ArrTimeMin = $_REQUEST['ArrTimeMin'];

		$DepTime = date('H:i', mktime($DepTimeHour, $DepTimeMin));
		$ArrTime = date('H:i', mktime($ArrTimeHour, $ArrTimeMin));
		
		$query = "SELECT COUNT(*) AS Count
							FROM trains
							WHERE TrainNumber = ".$TrainNo." AND
										DepartureTime = '".$DepTime."' AND
										ArrivalTime = '".$ArrTime."'";
		
		$res = @mysqli_query($conn, $query);
		$row = mysqli_fetch_array($res, MYSQL_ASSOC);
		if($row['Count'] > 0) {
			mysqli_free_result($res);
			mysqli_close($conn);
			echo "<script type='text/javascript'>alert('The train that you have entered already exists!');</script>";
		} else {
			mysqli_free_result($res);
			$query = "Select ID AS StationID FROM stations WHERE Station = '".$DepStat."'";
			$res = @mysqli_query($conn, $query);
			$numRows = mysqli_num_rows($res);
			if($numRows == 0) {
				mysqli_free_result($res);
				$query = "INSERT INTO stations (Station) VALUES ('".$DepStat."')";
				@mysqli_query($conn, $query);
				$DepStatID = mysqli_insert_id($conn);
			} else {
				$row = mysqli_fetch_array($res, MYSQL_ASSOC);
				$DepStatID = $row['StationID'];
				mysqli_free_result($res);
			}
			
			$query = "Select ID AS StationID FROM stations WHERE Station = '".$ArrStat."'";
			$res = @mysqli_query($conn, $query);
			$numRows = mysqli_num_rows($res);
			if($numRows == 0) {
				mysqli_free_result($res);
				$query = "INSERT INTO stations (Station) VALUES ('".$ArrStat."')";
				@mysqli_query($conn, $query);
				$ArrStatID = mysqli_insert_id($conn);
			} else {
				$row = mysqli_fetch_array($res, MYSQL_ASSOC);
				$ArrStatID = $row['StationID'];
				mysqli_free_result($res);
			}
			
			$query = "INSERT INTO trains (TrainNumber, IdDepartureStation, IdArrivalStation, DepartureTime, ArrivalTime) VALUES
				      (".$TrainNo.", ".$DepStatID.", ".$ArrStatID.", '".$DepTime."', '".$ArrTime."')";
			@mysqli_query($conn, $query);
			mysqli_close($conn);
			
			echo "<script type='text/javascript'>alert('New train inserted!');</script>";
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

			function checkNewTrain() {
		    if (isNaN(document.input_new_train.TrainNo.value)) {
		        alert("The train number field must contains numbers only!");
		        document.input_new_train.TrainNo.value = 0;
		        document.input_new_train.TrainNo.focus();
		        return (false);
		    }

		    if (document.input_new_train.TrainNo.value === "") {
		        alert("The train number field is empty!");
		        document.input_new_train.TrainNo.focus();
		        return (false);
		    }

		    if (parseInt(document.input_new_train.TrainNo.value) > 9999) {
			    alert("Train number must be a value between 0 and 9999");
			    document.input_new_train.TrainNo.focus();
			    return (false);
		    }

		    if (document.input_new_train.DepStat.value === "") {
		        alert("The departure station field is empty!");
		        document.input_new_train.DepStat.focus();
		        return (false);
		    }

		    if (document.input_new_train.DepStat.value === document.input_new_train.ArrStat.value) {
			  	  alert("Departure station is equal to the arrival station");
			   	 	return (false);
		    }

		    if (document.input_new_train.ArrStat.value === "") {
		        alert("The arrival station field is empty!");
		        document.input_new_train.ArrStat.focus();
		        return (false);
		    }

		    if (parseInt(document.input_new_train.DepTimeHour.value) > parseInt(document.input_new_train.ArrTimeHour.value)) {
		        alert("The arrival time is lower than the departure time!");
		        return (false);
		    }

		    if (document.input_new_train.DepTimeHour.value === document.input_new_train.ArrTimeHour.value &&
				    parseInt(document.input_new_train.DepTimeMin.value) >= parseInt(document.input_new_train.ArrTimeMin.value)) {
		        alert("The arrival time is lower than the departure time!");
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
						<li><a href="adm_del_train.php">Delete train</a></li>
						<li><i>Add new train</i></li>
						<li><a href="logout.php" onclick="return quitSession();">Logout</a></li>
					</ul>
				</div>
			</div>
			<div class="central_part">
				<div class="borders">
					Add new train...
				</div>
				<div id="new_train_form">
					<form action="adm_new_train.php" method="post" name="input_new_train" onsubmit="return checkNewTrain()">
						<fieldset>
							<legend>Insert here the information of the new train:</legend>
							Train Number: <input type="text" name="TrainNo" title="Insert here the number of the new train"><br>
							Departure Station: <input type="text" name="DepStat" title="Insert here the departure station of the new train">
							Departure Time: <select name="DepTimeHour" title="Select the hour of the departure time">
								<?php
									for($i=00; $i<24; $i++)
										echo "<option value=".$i.">".$i."</option>";
								?>
							</select>
							:
							<select name="DepTimeMin" title="Select the minutes of the departure time">
								<?php
									for($i=00; $i<60; $i++)
										echo "<option value=".$i.">".$i."</option>";
								?>
							</select><br>
							Arrival Station: <input type="text" name="ArrStat" title="Insert here the arrival station of the new train">
							Arrival Time: <select name="ArrTimeHour" title="Select the hour of the arrival time">
								<?php
									for($i=00; $i<24; $i++)
										echo "<option value=".$i.">".$i."</option>";
								?>
							</select>
							:
							<select name="ArrTimeMin" title="Select the minutes of the arrival time">
								<?php
									for($i=00; $i<60; $i++)
										echo "<option value=".$i.">".$i."</option>";
								?>
							</select>
						</fieldset>
						<input type="submit" name="insert" value="Insert" title="Insert the new train">
						<input type="reset" name="reset" value="Clear" title="Reset the fields">
						<input type="button" name="cancel" value="Cancel" onclick="window.location='administration.php'" title="Go back to the main page">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>