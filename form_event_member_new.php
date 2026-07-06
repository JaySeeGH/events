<?php
	error_reporting(E_ALL);
	include ('varMySQL.php');
  include ('var.php');
	$userid = 0;
	session_start();
	if(isset($_SESSION['userid'])){
		$userid = $_SESSION['userid'];
	}
	// Variabeln

	$memberid = 0;

	if($_GET and isset($_GET['memberid'])) {
		$memberid = htmlspecialchars($_GET['memberid']);
		$memberid = intval($memberid);
	}

	$whereStrM = $memberid > 0 ? " AND m.memberid=" . $memberid : "";

	$eventid = 0;

	if($_GET and isset($_GET['eventid'])) {
		$eventid = htmlspecialchars($_GET['eventid']);
		$eventid = intval($eventid);
	}

	$whereStr = $eventid > 0 ? " WHERE e.eventid=" . $eventid : "";

	$strSQLCity     		= "SELECT c.* FROM `city` c";
	$strSQLEvent     		= "SELECT e.* FROM `event` e " . $whereStr;
	$strSQLEventMember  = "SELECT e.*,m.*,a.event_memberid,a.signup, a.isPaid,a.md5hash FROM `event_member` a LEFT JOIN `member` m ON a.memberid = m.memberid LEFT JOIN `event` e ON a.eventid = e.eventid ". $whereStr . " ORDER BY m.Name ASC";
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

	if($userid == 0){
		header("Location: " . "login.php");
	}
?><!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="refresh" content="10" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alle Teilnehmer</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="./src/styles/app.css">-->
		<style>
		@media print {
			.no-print {
				display: none;
			}
		}

			.member-inn {
					color: white;
					background-color: green !important;

			}
			.count-big {
				font-weight: bold;
				font-size: 40pt;
				text-align: center;
			}
			.count-small{
				font-size: 8pt;
			}
			.count-max{
				color: red;
			}
			.count-reg{
				color: gray;
			}
			.count-inn{
				color: green;
			}
			.counter p {
				text-align: center;
			}
			.counter {
				border: 1px solid lightgray;
			}
			.btn {
				margin-top; 2px !important;
			}
			.paid {
				padding:  0 20px;
				font-weight: bold;
				font-size: 24px;
				line-height: 36px;
				vertical-align: middle;
			}
			.paid:hover {
				text-decoration: none;
			}

		</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-md-12">
			<div class="list-group">
				<a class="list-group-item list-group-item-action" href="form_member_new.php">neuer Teilnehmer</a>
			</div>
			</div>
		</div>
		<div class="row">
			<form action="form_Submit.php" method="post">
				<input type="hidden" name="table" value="member">
				<div class="col-xs-12 col-md-12">
					<div class="form-group">
						<label for="eventid">Veranstaltungen</label>
						<select name="eventid" id="eventid" class="form-control">" onchange="reloadForEvent(this)">
							 <?php
							 $countMax = 0;
							 foreach($outputEvent as $elemE) {

								  $outputEvent[] = $eventid === $elemE['eventid'] ? $elemE : [];
									$countMax = isset($elemE['countMax']) ? $elemE['countMax'] : 0;
									$selected = $eventid === $elemE['eventid'] ? " selected=\"selected\"" : "";
									echo "<option value=\"" . $elemE['eventid']."\"" . $selected . ">" . $elemE['event'] . "</option>\n";

							 }
							 ?>
						</select>
					</div>
				</div>
				<?php
				$countMembers = 0;
				$countMemberCheckInn = 0;
				foreach($outputEventMember as $elemEM) {
						if($elemEM['Name'] != null){
							$countMembers++;
	 					 if($elemEM['signup'] == 1) {
	 						 $countMemberCheckInn++;
	 					 }
						}


				}
				?>
				<div class="row">
					<div class="col-xs-12 col-md-12 col-sd-12">
						<div class="row counter">
							<div class="col-4">
								<p class="count-small">Teilnehmer<br>maximal</p>
								<p class="count-big count-max"><?php echo $countMax ?></p>
							</div>
							<div class="col-4">
								<p class="count-small">Teilnehmer<br>registriert</p>
								<p class="count-big count-reg"><?php echo $countMembers ?></p>
							</div>
							<div class="col-4">
								<p class="count-small">Teilnehmer<br>eingecheckt</p>
								<p class="count-big count-inn"><?php echo $countMemberCheckInn ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-12">
					<div>
						<?php $tempNr = 0;
						$showHead = 1; ?>
						<label>Teilnehmer</label>
						<table class="table table-bordered table-sm table-striped">

						<tbody>
							 <?php
							 $char = '';
							 usort($outputEventMember, function($a_, $b_) {
								return strcmp($a_['Name'], $b_['Name']);
							 });
							 foreach($outputEventMember as $elem) {
								 if($elem['Name'] != null && $elem['Name'][0] != '') {
								  if($char !== $elem['Name'][0]) {
										$char = $elem['Name'][0];
										echo '<tr><td colspan="3"><b>' . $char . "</b></td></tr>\n";
									}
									$signupClass = $elem['signup'] == 1 ? ' class="member-inn" ' : '';
									echo "<tr" . $signupClass . "><td>";
									if($elem['signup'] != 1) {
										echo "<a href='checkInn.php?mid=". $elem['memberid'] ."'>";
									}
									echo $elem['Vorname'] . " " . $elem['Vorname2'] . " " . $elem['Name'];
									if($elem['signup'] != 1) {
										echo "</a>";
									}
									echo "</td><td>" . $elem['strasse'] . "</br>" . $elem['plz']. " " . $elem['city'];
									echo "<td";
									if($elem['isPaid'] == 1) {
											echo " style='background-color: #70C7F9 !important' ";
									}else{
											echo " style='background-color: #FFFF00 !important' ";
									}
									echo ">";
									if($elem['isPaid'] == 1) {
											echo "<span class='paid'>€</span>";
									} else {
										echo "<a class='paid' href='paidInn.php?mid=". $elem['memberid'] ."'>€</a>";
									}
									echo "</td></tr>\n";
							 	}
							 }
							 ?>
						 </tbody>
						</table>
					</div>
				</div>
			</form>
		</div>
	</div>
	<script>
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
