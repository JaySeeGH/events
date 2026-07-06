<?php

	error_reporting(E_ALL);
	include ('varMySQL.php');
	include ('var.php');

	// Variabeln

	$eventid = 0;

	if($_GET and isset($_GET['eventid'])) {
		$eventid = htmlspecialchars($_GET['eventid']);
		$eventid = intval($eventid);
	}

	$whereStr = $eventid > 0 ? " WHERE e.eventid=" . $eventid : "";

	$strSQLCity     		= "SELECT c.* FROM `city` c";
	$strSQLEvent     		= "SELECT e.* FROM `event` e " . $whereStr;
	$strSQLEventMember  = "SELECT e.*,m.*,a.md5hash FROM `event_member` a LEFT JOIN `member` m ON a.memberid = m.memberid LEFT JOIN `event` e ON a.eventid = e.eventid ". $whereStr . " ORDER BY m.Name ASC";
	$strSQLFirma    		= "SELECT f.* FROM `firma` f";
	$strSQLGeschlecht 	= "SELECT g.* FROM `geschlecht` g";
	$strSQLInnung     	= "SELECT i.* FROM `innung` i";
	//$strSQLMember	  		= "SELECT m.* FROM `member` m WHERE m.active = 1 " . $whereStrM;
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
	//$queryMember 				= mysqli_query($con,$strSQLMember)or die($con->error);
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

	/*$outputEventMember = [];

	while(($rowEventMember = $queryEventMember->fetch_assoc()) !== null) {
		$outputEventMember[] = $rowEventMember;
	}*/

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



	$outputUser = [];

	while(($rowUser = $queryUser->fetch_assoc()) !== null) {
		$outputUser[] = $rowUser;
	}

	mysqli_close($con);

	foreach($outputEvent as $elem) {

		 $outputEvent[] = $eventid === $elem['eventid'] ? $elem : [];

	}
?><!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Veranstaltung</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<style>
	@media print {
		.no-print {
			display: none;
		}
	}

		.navbar-brand {
				margin-left: 15px;
				height: 106px;
				width: 220px;
				background: url('<?php echo $logo ?>') no-repeat left center;
				background-image: url('<?php echo $logo ?>'),none;
				background-size: 220px;
			}


	</style>
</head>
<body>
	<div class="container">
		<div class="row">
				<div id="logo" class="col-md-3 col-sm-4"><a class="navbar-brand" href="<?php echo $destHWK ?>"></a></div>
		</div>
		<div class="row">
			<div class="col-xs-6 col-md-12">
				<p>Die Veranstaltung <?php echo $elem['event'] ?> findet am <?php $aDateFrom = explode("-",$elem['datefrom']); echo $aDateFrom[2] . "." .$aDateFrom[1] . "." .$aDateFrom[0] . " um " . $elem['beginEvent'] . " Uhr " ?> statt.</p>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
