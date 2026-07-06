<?php

	error_reporting(E_ALL);
	include ('varMySQL.php');
	include ('var.php');

	// Variabeln
	$userid = 0;

	session_start();
	if(isset($_SESSION['userid'])){
		$userid = $_SESSION['userid'];
	}

	$memberid = 0;

	if($_GET and isset($_GET['mid'])) {
		$memberid = htmlspecialchars($_GET['mid']);
		$memberid = intval($memberid);
	}

	$whereStrM = $memberid > 0 ? " AND m.memberid=" . $memberid : "";
	$whereStr = $memberid > 0 ? " WHERE a.memberid=" . $memberid : "";

	$eventid = 0;

	if($_GET and isset($_GET['eventid'])) {
		$eventid = htmlspecialchars($_GET['eventid']);
		$eventid = intval($eventid);
	}

	$md5hash = 0;

	if($_GET and isset($_GET['meid'])) {
		$md5hash = htmlspecialchars($_GET['meid']);

	}

	$whereStrE = $eventid > 0 ? " WHERE e.eventid=" . $eventid : "";

	$strSQLCity     		= "SELECT c.* FROM `city` c";
	$strSQLEvent     		= "SELECT e.* FROM `event` e " . $whereStrE;
	$strSQLEventMember  = "SELECT e.*,m.*,a.event_memberid,a.signup, a.isPaid, a.md5hash FROM `event_member` a LEFT JOIN `member` m ON a.memberid = m.memberid LEFT JOIN `event` e ON a.eventid = e.eventid ". $whereStr . " ORDER BY m.Name ASC";
	$strSQLFirma    		= "SELECT f.* FROM `firma` f";
	$strSQLGeschlecht 	= "SELECT g.* FROM `geschlecht` g";
	$strSQLInnung     	= "SELECT i.* FROM `innung` i";
	$strSQLMember	  		= "SELECT m.* FROM `member` m WHERE m.active = 1 " . $whereStrM;
	$strSQLUser			  	= "SELECT u.* FROM `user` u";// FT JOIN gv_Members p ON a.Memberid = p.Memberid " . $whereStr;"

	//echo $strSQLEvent;
	// Connect to Database
	define('HOST',$MySQLHost);
	define('USER',$MySQLUsername);
	define('PASS',$MySQLPassword);
	define('DB',  $MySQLDB);
	$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');

	// Set Charset to UTF8
	mysqli_set_charset($con, "utf8");

	// Read Data
	// $queryFunctions = mysqli_query($con,$strSQLFunctions);
	$queryCity  				= mysqli_query($con,$strSQLCity) or die($con->error);
	$queryEvent  				= mysqli_query($con,$strSQLEvent)or die($con->error);
	$queryEventMember 	= mysqli_query($con,$strSQLEventMember)or die($con->error);
	$queryFirma 				= mysqli_query($con,$strSQLFirma)or die($con->error);
	$queryGeschlecht    = mysqli_query($con,$strSQLGeschlecht)or die($con->error);
	$queryInnung 				= mysqli_query($con,$strSQLInnung)or die($con->error);
	$queryMember 				= mysqli_query($con,$strSQLMember)or die($con->error);
	$queryUser  				= mysqli_query($con,$strSQLUser)or die($con->error);

	//$outputEvent   = array();
	$outputCity = [];

	while($rowCity = $queryCity->fetch_assoc()) {
		$outputCity[] = $rowCity;
	}

	$outputEvent = [];

	while(($rowEvent = $queryEvent->fetch_assoc()) !== null) {
		$outputEvent[] = $rowEvent;
	}

	$outputEventMember = [];

	while(($rowEventMember = $queryEventMember->fetch_assoc()) !== null) {
		$outputEventMember[] = $rowEventMember;
	}

	$outputFirma = [];

	while(($rowFirma = $queryFirma->fetch_assoc()) !== null) {
		$outputFirma[] = $rowFirma;
	}

	$outputGeschlecht = [];

	while(($rowGeschlecht = $queryGeschlecht->fetch_assoc()) !== null) {
		$outputGeschlecht[] = $rowGeschlecht;
	}

	$outputInnung = [];

	while(($rowInnung = $queryInnung->fetch_assoc()) !== null) {
		$outputInnung[] = $rowInnung;
	}

	$outputMember = [];

	while(($rowMember = $queryMember->fetch_assoc()) !== null) {
		$outputMember[] = $rowMember;
	}

	$outputUser = [];

	while(($rowUser = $queryUser->fetch_assoc()) !== null) {
		$outputUser[] = $rowUser;
	}

	mysqli_close($con);
	if($userid === 0) {
		header("Location: " . "checkInnMember.php?eventid=" . $eventid);
	}
	$alarm = 0;
?><!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teilnehmer</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="./src/styles/app.css">-->
		<style>
		.redAlert{
			 background-color: red;
		}
		a.paid {
			margin: 20px;
		}
		</style>
</head>
<body id="red">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<h3>Bezahlung Begleitperson oder Teilnehmer auf "Bezahlt" setzen</h3>
			</div>
		</div>
		<div class="row">

			<form action="form_Submit.php" method="post">
				<input type="hidden" name="table" value="event_member">
				<input type="hidden" name="event_memberid" value="<?php echo $outputEventMember[0]['event_memberid'] ?>">
				<input type="hidden" name="eventid" value="<?php echo $outputEventMember[0]['eventid'] ?>">
				<input type="hidden" name="memberid" value="<?php echo $outputEventMember[0]['memberid'] ?>">
				<input type="hidden" name="signup" value="<?php echo $outputEventMember[0]['signup'] ?>">
				<input type="hidden" name="isPaid" value="1">
				<input type="hidden" name="userid" value="<?php echo $userid ?>">
				<input type="hidden" name="md5hash" value="<?php echo $outputEventMember[0]['md5hash'] ?>">
				<!--<div class="row">-->
					<div class="col-xs-12 col-md-12">
					<div class="form-group">
						<label for="eventid">Veranstaltungen</label>
						<select name="eventid" id="eventid" class="form-control" onchange="reloadForEvent(this)">

							 <?php
							 foreach($outputEvent as $elemE) {

								  $outputEvent[] = $eventid === $elemE['eventid'] ? $elem : [];
									$selected = $eventid === $elemE['eventid'] ? " selected=\"selected\"" : "";
									echo "<option value=\"" . $elemE['eventid']."\"" . $selected . ">" . $elemE['event'] . "</option>\n";

							 }
							 ?>
						</select>
					</div>
					</div>
				<!--</div>
				<div class="row">-->
					<div class="col-xs-12 col-md-12">
						<?php $tempNr = 0;
						$showHead = 1; ?>
						<label>Teilnehmer</label>
						<table class="table table-bordered table-sm table-striped">

						<tbody>
							 <?php

							 foreach($outputEventMember as $elem) {

								 	//$aDateFrom = explode("-",$elem['datefrom']);
									//$aDateTo = explode("-",$elem['dateto']);
									echo "<tr";
									if($elem['signup'] != 0) {
										echo " style='background-color: #FFCCCB !important' ";
										$alarm = 1;
									}
									elseif($elem['isPaid'] == 0) {
										echo " style='background-color: #FFFF00 !important' ";
										$alarm = 0;
									} elseif($elem['isPaid'] == 1) {
										echo " style='background-color: #70C7F9 !important' ";
										$alarm = 0;
									}
									echo "><td class='text-center'>";
									//if($elem['signup'] == 0) {
									//	echo "<a href='checkInn.php?mid=". $elem['memberid'] ."'>";

									//}
									echo $elem['Vorname'] . " " . $elem['Vorname2'] . " " . $elem['Name'];
									//if($elem['signup'] == 0) {
									//	echo "</a>";
									//}
									echo "</td>";
									echo "<td>" . $elem['strasse'] . "</br> " . $elem['plz'] . " " . $elem['city']. "</td>";
									echo "<td>";
									if($elem['Funktion'] != '') {
										echo "Funktion: " . $elem['Funktion'] . "</br>";
									}
									if($elem['BegleitpersonVon'] != '') {
										echo "Begleitperson von " . $elem['BegleitpersonVon'];
									}
									echo "<td>";
									if($elem['isPaid'] == 1) {
											echo "<span class='paid'>€</span>";
									} else {
										echo "<a class='paid' href='paidInn.php?mid=". $elem['memberid'] ."'>€</a>";
									}

									echo "</td></tr>\n";

							 }
							 ?>
						 </tbody>
						</table>
					</div>
				<!--</div>-->




				<div class="col-xs-12 col-md-12">
					<button type=<?php if($memberid === 0) {echo '"button" onclick="goToList(this.form)"'; } else { echo '"submit"';} ?> class="btn btn-primary col-md-6"><?php $aendern = $memberid === 0 ?  "aktualisieren" :  "Bezahlung bestätigen"; echo $aendern; ?></button>
				</div>
			</form>
		</div>
		</div>
	</div>
	<script>
	<?php if($alarm == 1){
		echo 'document.getElementById("red").classList.add("redAlert")';
	}
	?>

	function goToList(form) {
			var e = document.getElementById("eventid");
			var eventid = e.value;
			window.location.href = 'form_event_member_new.php?eventid=' + eventid;
	}
	function reloadForEvent(value_) {
		var url = window.location.href;
		if (url.indexOf('?') > -1){
			var aUrl = url.split("?");
			var aParams = aUrl[1].split("&");
			var aParamsNew = [];
			for(var i = 0; i < aParams.length; i++) {
				var aPair = aParams[i].split("=");
				if(aPair[0] !== "eventid") aParamsNew.push(aParams[i])
			}
			var sep = aParamsNew.length === 0 ? '' : '&';
			url = aUrl[0] + '?' + aParamsNew.join("&") + sep + 'eventid=' + value_.value;


		}else{
   			url += '?eventid=' +value_.value;
		}
		window.location.href = url;
	}
	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
