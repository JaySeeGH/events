<?php

	error_reporting(E_ALL);
	include ('varMySQL.php');
	include ('var.php');

	// Variabeln

	$eventid = 0;

	if($_GET and isset($_GET['eid'])) {
		$eventid = htmlspecialchars($_GET['eid']);
		$eventid = intval($eventid);
	}

	// QR-Code basteln

	$data = $destCheckInn . "meid=" . $_GET['meid'] . "&eventid=" . $_GET['eid'] . "&mid=" . $_GET['m'];

	$whereStr = $eventid > 0 ? " WHERE e.eventid=" . $eventid : "";

	$memberid = 0;

	if($_GET and isset($_GET['memberid'])) {
		$memberid = htmlspecialchars($_GET['memberid']);
		$memberid = intval($memberid);
	}

	$md5hash = '';

	if($_GET and isset($_GET['meid'])) {
		$md5hash = htmlspecialchars($_GET['meid']);
	}

	$firmaID = 0;

	if($_GET and isset($_GET['firmaid'])) {
		$firmaID = htmlspecialchars($_GET['firmaid']);
	}

	$whereStrM = $memberid > 0 ? " AND m.memberid=" . $memberid : "";

	$userid = 0;

	$whereStrM = $userid > 0 ? $whereStrM : $whereStrM . " AND m.md5hash='" . $md5hash . "'";

	$strSQLCity     		= "SELECT c.* FROM `city` c";
	$strSQLEvent     		= "SELECT e.* FROM `event` e " . $whereStr;
	$strSQLEventMember  = "SELECT e.*,m.* FROM `event_member` a LEFT JOIN `member` m ON a.memberid = m.memberid LEFT JOIN `event` e ON a.eventid = e.eventid ". $whereStrM;
	$strSQLFirma    		= "SELECT f.* FROM `firma` f";
	$strSQLGeschlecht 	= "SELECT g.* FROM `geschlecht` g";
	$strSQLInnung     	= "SELECT i.* FROM `innung` i";
	$strSQLMember	  		= "SELECT m.* FROM `member` m WHERE m.active = 1 " . $whereStrM;
	$strSQLUser			  	= "SELECT u.* FROM `user` u";// FT JOIN gv_Members p ON a.Memberid = p.Memberid " . $whereStr;"

	// echo $strSQLMember;
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
?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR-Code für Teilnehmer</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="./src/styles/app.css">-->
		<style>
		.print-logo {
			display: none;
		}
		@media print {
			.no-print {
				display: none;
			}
			.print-logo {
				display: block;
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
			.btn.no-print {
				margin-top: 2px !important;
			}
		</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row no-print ">
				<div id="logo" class="col-md-3 col-sm-4"><a class="navbar-brand" href="<?php echo $destHWK ?>"></a></div>
		</div>
		<div class="row print-logo">
				<img src="<?php echo $logo ?>">
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<h1>Veranstaltung:
					<?php

					 foreach($outputEvent as $elem) {

							$outputEvent[] = $elem['eventid'] === $_GET['eid'] ? $elem : [];
							$aDateFormat = explode("-",$elem['datefrom']);
							echo $elem['event'] . "</h1>" .
							"<p>Beginn: " . $aDateFormat[2] ."." . $aDateFormat[1] ."." . $aDateFormat[0]. " </p>".
							"<p>". $elem['description']. " </p>";

					 }
					 ?>

			</div>
		</div>
		<div class="row">

			<div class="col-xs-6 col-md-4">

					<?php
					//set it to writable location, a place for temp generated PNG files
					$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

					//html PNG location prefix
					$PNG_WEB_DIR = 'temp/';

					include "phpqrcode/qrlib.php";

					//ofcourse we need rights to create temp dir
					if (!file_exists($PNG_TEMP_DIR))
							mkdir($PNG_TEMP_DIR);


					$filename = $PNG_TEMP_DIR.'test.png';

					//processing form input
					//remember to sanitize user input in real-life solution !!!
					$errorCorrectionLevel = 'L';
					if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
							$errorCorrectionLevel = $_REQUEST['level'];

					$matrixPointSize = 4;
					if (isset($_REQUEST['size']))
							$matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


					if (isset($data)) {

							//it's very important!
							if (trim($data) == '')
									die('data cannot be empty! <a href="?">back</a>');

							// user data
							$filename = $PNG_TEMP_DIR.'test'.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
							QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

					} else {

							//default data
							//echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';
							QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);

					}

					//display generated file
					if(isset($qrClick) && $qrClick === 1) {
						echo '<a href="'.$data.'" target="_blank" >';
					}
					echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" />';
					if(isset($qrClick) && $qrClick === 1) {
						echo '</a>';
					}
					?>

			</div>
			<div class="col-xs-6 col-md-4">



				<?php
				foreach($outputMember as $elem) {

					 $outputMember[] = $memberid === $elem['memberid'] ? $elem : [];


				}
				?>
						<h2>Anmeldung</h2>
						<?php $titel = isset($elem['titel']) ? $elem['titel'] : '';
						if($titel == 0) $titel = '' ?>
						<?php if($titel !== '') echo "<p>" . $titel . "</p>";
						$vorname2 = '';
						if($elem['Vorname2'] !== '' ) {
							$vorname2 = ' ' . $elem['Vorname2'];
						}
						echo "<p>" . $elem['Vorname'] . $vorname2 . " " . $elem['Name'] . "</p>";
						if($elem['Funktion'] != '') {
							echo "<p><b>Funktion: " . $elem['Funktion'] . "</b></p>";
						}
						if($elem['BegleitpersonVon'] != '') {
							echo "<i>Begleitperson von : " . $elem['BegleitpersonVon'] . "</i></p>";
						}
						echo "<p>" . $elem['strasse'] . "</p>";
						echo "<p>" . $elem['plz'] . " " . $elem['city'] . "</p>";

						foreach($outputInnung as $elemI) {
								if($elem['innungid'] === $elemI['innungid']) {
									echo "<p>" . $elemI['innung'] . "</p>\n";
								}

						}


						foreach($outputFirma as $elemF) {
								if($elem['firmaid'] === $elemF['firmaid']){
									echo "<p>" . $elemF['firma'] . "</p>\n";
								}

						}

							 ?>

		</div>
	</div>
	<div class="row">
		<div class="col">
			<a class="btn btn-secondary col-md no-print" href="form_member_new.php?eventid=<?php echo $eventid?>">weiteren Teilnehmer anmelden</a>
		</div>
		<div class="col">
			<a class="btn btn-primary col-md no-print" id="drucken" href="#">Ausdrucken</a>
		</div>
		<div class="col">
			<a class="btn btn-secondary col-md no-print" href="<?php echo $destHWK ?>">zur Kreishandwerkerschaft Lindau</a>
		</div>
	</div>
	</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
	document.querySelector("#drucken").addEventListener("click", function () {
			window.print();
		});
});
</script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
