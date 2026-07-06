<?php

	error_reporting(E_ALL);
	include ('varMySQL.php');
	include ('var.php');

	// Variabeln

	// Nutzer ID für Administration ermitteln
	$userid = 0;

	session_start();
	if(isset($_SESSION['userid'])){
		$userid = $_SESSION['userid'];
	}

	// Heute
	$now = date("Y-m-d");

	// Event ID ermitteln
	$eventid = 0;

	if($_GET and isset($_GET['eventid'])) {
		$eventid = htmlspecialchars($_GET['eventid']);
		$eventid = intval($eventid);
	}

	// Abfragebedingung für Veranstaltung, die im Zeitrahmen sichtbar sind
	$whereStr = $eventid > 0 ? " WHERE e.eventid=" . $eventid ." AND e.showFrom < '".$now."'": " WHERE e.showFrom < '".$now."' AND  e.dateLastRegistration > '".$now."'";

	// Teilnehmer ID ermitteln
	$memberid = 0;

	if($_GET and isset($_GET['memberid'])) {
		$memberid = htmlspecialchars($_GET['memberid']);
		$memberid = intval($memberid);
	}

	// Sicherheitstoken ermitteln
	$md5hash = 0;

	if($_GET and isset($_GET['emid'])) {
		$md5hash = htmlspecialchars($_GET['emid']);
	}

	// Firma ID ermitteln
	$firmaID = 0;

	if($_GET and isset($_GET['firmaid'])) {
		$firmaID = htmlspecialchars($_GET['firmaid']);
	}

	// Abfrage zum Teilnehmer ermitteln

	$whereStrM = " AND m.memberid=" . $memberid;

	$whereStrM = $userid > 0 ? $whereStrM : $whereStrM . " AND m.md5hash=" . $md5hash;

	$strSQLCity     		= "SELECT c.* FROM `city` c";
	$strSQLEvent     		= "SELECT e.* FROM `event` e " . $whereStr;
	$strSQLEventMember  = "SELECT e.*,m.* FROM `event_member` a LEFT JOIN `member` m ON a.memberid = m.memberid LEFT JOIN `event` e ON a.eventid = e.eventid ". $whereStr;
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
	$event = '';

	foreach($outputEvent as $elem) {

			$outputEvent[] = $eventid === $elem['eventid'] ? $elem : [];
			$selected = $eventid === $elem['eventid'] ? " selected=\"selected\"" : "";
			$aDateFormat = explode("-",$elem['datefrom']);
			$event = $eventid === $elem['eventid'] ? $elem['event'] : '';
			$additionalFields = $eventid === $elem['eventid'] ? $elem['additionalFields'] : '{}';
			$additionalFieldsObj = json_decode($additionalFields,true);
	}
?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php $eventE = $event !== '' ? $event . ' ' : ''; echo $eventE ?>Neuer Teilnehmer</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="./src/styles/app.css">-->
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
				‚background-size: 220px;
		}


		</style>
</head>
<body>
	<div class="container-fluid">
	<!--<div class="row">
		<ul>
			<li><a href="form_members_new.php">alle Veranstaltungen</a></li>
			<li><a href="form_member_new.php">neuer Teilnehmer</a></li>
		</ul>
	</div>-->
	<div class="row">
			<div id="logo" class="col-md-3 col-sm-4"><a class="navbar-brand" href="<?php echo $destHWK ?>"></a></div>
	</div>
		<div class="row">
			<form action="form_Submit.php" method="post" class="needs-validation">
				<input type="hidden" name="table" value="member">
				<input type="hidden" name="table2" value="event_member">
				<div class="col-xs-12 col-md-12">
					<!--<div class="row">-->
						<div class="form-group">
							<label for="eventid">Veranstaltung</label>

							<select name="eventid" id="eventid" class="form-control" onchange="reloadForEvent(this)">
							<?php if($eventid == 0) { echo "<option>bitte wählen</option>";}
							$event = '';

							 foreach($outputEvent as $elem) {

								  $outputEvent[] = $eventid === $elem['eventid'] ? $elem : [];
									$selected = $eventid === $elem['eventid'] ? " selected=\"selected\"" : "";
									$aDateFormat = explode("-",$elem['datefrom']);
									$event = $eventid === $elem['eventid'] ? $elem['event'] : '';
									if($aDateFormat[0] && $aDateFormat[0] !== ''){
										echo "<option value=\"" . $elem['eventid']."\"" . $selected . ">" . $elem['event'] . " (" . $aDateFormat[2] ."." . $aDateFormat[1] ."." . $aDateFormat[0]. ")</option>\n";

									}




							 }
							 ?>
							</select>
							<input type="hidden" name="event" value="<?php echo $event ?>">
						</div>
					<!--</div>-->
				</div>
				<div class="col-xs-6 col-md-12">

					<?php
					foreach($outputMember as $elem) {

					 	$outputMember[] = $memberid === $elem['memberid'] ? $elem : [];


					}
					$readonly = "readonly ";
					if($eventid > 0) $readonly = '';
					?>
					<!--<div class="row">-->
						<div class="form-group">
							<label for="titel">Titel</label>
							<?php $titel = isset($elem['titel']) ? $elem['titel'] : '';
							if($titel == 0) $titel = '' ?>
							<input <?php echo $readonly ?>name="titel" id="titel" class="form-control" value="<?php echo $titel ?>">
						</div>
					<!--</div>-->
			</div>
			<div class="col-xs-6 col-md-12">

				<!--<div class="row">-->
					<div class="form-group">
						<label for="Vorname">Vorname *</label>
						<?php $Vorname = isset($elem['Vorname']) ? $elem['Vorname'] : ''; ?>
						<input required <?php echo $readonly ?>name="Vorname" id="Vorname" class="form-control" value="<?php echo $Vorname ?>">
					</div>
				<!--</div>-->
				<!--<div class="row">-->
					<div class="form-group">
						<label for="Vorname2">2. Vorname</label>
						<?php $Vorname2 = isset($elem['Vorname2']) ? $elem['Vorname2'] : ''; ?>
						<input <?php echo $readonly ?>name="Vorname2" id="Vorname2" class="form-control" value="<?php echo $Vorname2 ?>">
					</div>
				<!--</div>-->
				<!--<div class="row">-->
					<div class="form-group">
						<label for="Name">Nachname *</label>
						<?php $Name = isset($elem['Name']) ? $elem['Name'] : ''; ?>
						<input required <?php echo $readonly ?>name="Name" id="Name" class="form-control" value="<?php echo $Name ?>">
					</div>
				<!--</div>-->
				<!--<div class="row">-->
					<div class="form-group">
						<label for="Zeichen">Zeichen</label>
						<?php $Zeichen = isset($elem['Zeichen']) ? $elem['Zeichen'] : ''; ?>
						<input <?php echo $readonly ?>name="Zeichen" id="Zeichen" class="form-control" value="<?php echo $Zeichen ?>">
					</div>
				<!--</div>-->
				</div>
				<div class="col-xs-6 col-md-12">
				<!--<div class="row">-->
					<div class="form-group">
						<label for="Funktion">Funktion</label>
						<?php $Funktion = isset($elem['Funktion']) ? $elem['Funktion'] : ''; ?>
						<input <?php echo $readonly ?>name="Funktion" id="Funktion" class="form-control" value="<?php echo $Funktion ?>">
					</div>
				</div>
				<div class="col-xs-6 col-md-12">
				<!--<div class="row">-->
					<div class="form-group">
						<label for="BegleitpersonVon">Begleitperson von </label>
						<?php $BegleitpersonVon = isset($elem['BegleitpersonVon']) ? $elem['BegleitpersonVon'] : ''; ?>
						<input <?php echo $readonly ?>name="BegleitpersonVon" id="BegleitpersonVon" class="form-control" value="<?php echo $BegleitpersonVon ?>">
					</div>
				</div>
				<div class="col-xs-6 col-md-12">
				<!--<div class="row">-->
					<div class="form-group">
						<label for="strasse">Straße *</label>
						<?php $strasse = isset($elem['strasse']) ? $elem['strasse'] : ''; ?>
						<input required <?php echo $readonly ?>name="strasse" id="strasse" class="form-control" value="<?php echo $strasse ?>">
					</div>
				<!--</div>-->
				<!--<div class="row">-->
					<div class="form-group">
						<label for="plz">Postleitzahl *</label>
						<?php $plz = isset($elem['plz']) ? $elem['plz'] : ''; ?>
						<input required <?php echo $readonly ?>name="plz" id="plz" class="form-control" value="<?php echo $plz ?>">
					</div>
				<!--</div>-->
				<!--<div class="row">-->
					<div class="form-group">
						<label for="city">Stadt *</label>
						<?php $city = isset($elem['city']) ? $elem['city'] : ''; ?>
						<input required <?php echo $readonly ?>name="city" id="city" class="form-control" value="<?php echo $city ?>">
						<div class="invalid-feedback">
        			Geben Sie bitte eine Stadt ein.
      			</div>
					</div>
				<!--</div>-->
				<!--<div class="row">-->
					<div class="form-group">
						<label for="land">Land *</label>
						<?php $land = isset($elem['land']) ? $elem['land'] : ''; ?>
						<input required <?php echo $readonly ?>name="land" id="land" class="form-control" value="<?php echo $land ?>">
					</div>
					<div class="form-group">
						<label for="company">Firma</label>
						<?php $land = isset($elem['company']) ? $elem['company'] : ''; ?>
						<input <?php echo $readonly ?>name="company" id="company" class="form-control" value="<?php echo $company ?>">
					</div>				<!--</div>-->
			</div>
			<div class="col-xs-6 col-md-12">
				<p>Geben Sie entweder Telefon, Mobiltelefon oder E-Mailadresse an.</p>
			</div>
			<div class="col-xs-6 col-md-12">
				<!--<div class="row">-->
					<div class="form-group">
						<label for="telefon">Telefon</label>
						<?php $telefon = isset($elem['telefon']) ? $elem['telefon'] : '';
						if($telefon == 0) $telefon = '' ?>
						<input <?php echo $readonly ?>name="telefon" id="telefon" class="form-control" value="<?php echo $telefon ?>">
					</div>
				<!--</div>-->
				<!--<div class="row">-->
					<div class="form-group">
						<label for="mobil">Mobil</label>
						<?php $mobil = isset($elem['mobil']) ? $elem['mobil'] : '';
						if($mobil == 0) $mobil = '' ?>
						<input <?php echo $readonly ?>name="mobil" id="mobil" class="form-control" value="<?php echo $mobil ?>">
					</div>
				<!--</div>-->
				<!--<div class="row">-->
					<div class="form-group">
						<label for="email">E-Mail-Adresse</label>
						<?php $email = isset($elem['email']) ? $elem['email'] : '';
						if($email == 0) $email = '' ?>
						<input <?php echo $readonly ?>name="email" id="email" class="form-control" typ="email" value="<?php echo $email ?>">
					</div>
				<!--</div>-->
			</div>
			<div class="col-xs-6 col-md-12">
				<!--<div class="row">-->
					<div class="form-group">
						<label for="geburtstag">Geburtstag</label>
						<?php $geburtstag = isset($elem['geburtstag']) ? $elem['geburtstag'] : '';
						if($geburtstag === '0000-00-00'){ $geburtstag = '';} ?>
						<input <?php echo $readonly ?>name="geburtstag" id="geburtstag" class="form-control" value="<?php echo $geburtstag ?>">
					</div>
				<!--</div>-->
			</div>
				<div class="col-xs-6 col-md-12">
				<!--<div class="row">-->
					<div class="form-group">
						<label for="Notiz">Notiz</label>
						<?php $Notiz = isset($elem['Notiz']) ? $elem['Notiz'] : ''; ?>
						<textarea <?php echo $readonly ?>name="Notiz" id="Notiz" class="form-control" ><?php echo $Notiz ?></textarea>
					</div>
				<!--</div>-->
			</div>
				<div class="col-xs-6 col-md-12">
				<!--<div class="row">-->
					<input type="hidden" name="innungid" value="0">
				<!--</div>-->
				<!--<div class="row">-->
					<div class="form-group">
						<label for="geschlechtid">Geschlecht</label>
						<select <?php echo $readonly ?>name="geschlechtid" id="geschlechtid" class="form-control">
							<option value="0">bitte wählen</option>
							 <?php
							 //asort($outputGroups, function($a_, $b_) {
							//	return strcmp($a_['number'], $b_['number']);
							 //});
							 usort($outputGeschlecht, function($a_, $b_) {
								return strcmp($a_['geschlecht'], $b_['geschlecht']);
							 });
							 foreach($outputGeschlecht as $elem) {
								$selected = $geschlechtID === $elem['geschlechtid'] ? " selected=\"selected\"" : "";
								echo "<option value=\"" . $elem['geschlechtid'] . "\" " . $selected . ">" . $elem['geschlecht'] . "</option>\n";
							 }
							 ?>
						</select>
					</div>
				<!--</div>-->
				<!--<div class="row">-->

					<input type="hidden" name="firmaid" value="0">
				<!--</div>-->
			</div>
			
			<div class="col-xs-6 col-md-12">
				<p>Bitte füllen Sie die Felder mit * aus.
			</div>
			<div class="col-xs-6 col-md-12">
				<!--<div class="row">-->
					<label for="active">Zustimmung Datenschutz&nbsp;&nbsp;</label>
					<input <?php echo $readonly ?>type="checkbox" id="approval" name="approval" value="1" >
					<p>Ich stimme der Verarbeitung meiner Daten zu. Weitere Informationen erhalten Sie in unserer <a href="<?php echo $destHWKDatenschutz ?>" target="_blank">Datenschutzerklärung.</a></p>
				</div>

					<div class="col-xs-12 col-md-12">
						<div class="form-group">
							<button <?php echo $readonly ?>type="button" class="btn btn-primary col-md-6" onclick="approvalSubmit(this.form)"><?php $aendern = $memberid === 0 ?  "anmelden" :  "ändern"; echo $aendern; ?></button>
							<a class="btn btn-secondary col-md-5 no-print" href="<?php echo $destHWK ?>">zur Kreishandwerkerschaft Lindau</a>
						</div>
				</div>
			</div>
			</form>
		</div>
	</div>
	<script>
	function approvalSubmit(form){
		var req = 1;
		var aFields = [];
		if (form.Vorname.value === '') {
			req = 0;
			aFields.push("Vorname")
		}
		if (form.Name.value === '') {
			req = 0;
			aFields.push("Name")
		}
		if (form.strasse.value === '') {
			req = 0;
			aFields.push("strasse")
		}
		if (form.plz.value === '') {
			req = 0;
			aFields.push("plz")
		}
		if (form.city.value === '') {
			req = 0;
			aFields.push("city")
		}
		if (form.land.value === '') {
			req = 0;
			aFields.push("land")
		}
		if (form.mobil.value !== '') {
			tel = 1;
		}
		if (form.email.value !== '') {
			tel = 1;
		}
		var tel = 0;
		if (form.telefon.value !== '') {
			tel = 1;
		}
		if (form.mobil.value !== '') {
			tel = 1;
		}
		if (form.email.value !== '') {
			tel = 1;
		}

		if (tel === 0){
			alert('Bitte geben Sie Telefon, Mobiltelefon oder E-Mail an.');
			form.approval.checked = false;
		}
		if (req === 0){
			alert('Bitte füllen Sie die erforderlichen Felder aus');
			form.classList.add("was-validated");
			form.approval.checked = false;
		}

		if (form.approval.checked && tel === 1 && req === 1){
			form.submit();
		}
		else {
			if(!form.approval.checked){
				alert('Bitte stimmen Sie der Datenschutzerklärung zu.');
			}

		}
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
