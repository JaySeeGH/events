<?php
	error_reporting(E_ALL);
	session_start();
	include ('varMySQL.php');
	include ('var.php');

	$prefix = "";

	$whereString = "";

	$form = "";

	$aMd5Hash = ['Vorname','Name','strasse','plz','city','land'];

	$md5hash = createMd5Hash($_POST,$aMd5Hash);

	if(isset($_POST['table'])) {
		$titel = isset($_POST['titel']) ? htmlspecialchars($_POST['titel']) : '';
		$Vorname = isset($_POST['Vorname']) ? htmlspecialchars($_POST['Vorname']) : '';
		$Vorname2 = isset($_POST['Vorname2']) ? htmlspecialchars($_POST['Vorname2']) : '';
		$Name = isset($_POST['Name']) ? htmlspecialchars($_POST['Name']) : '';
		$Zeichen = isset($_POST['Zeichen']) ? htmlspecialchars($_POST['Zeichen']) : '' ;
		$Funktion = isset($_POST['Funktion']) ? htmlspecialchars($_POST['Funktion']) : '';
		$BegleitpersonVon = isset($_POST['BegleitpersonVon']) ? htmlspecialchars($_POST['BegleitpersonVon']) : '';
		$strasse = isset($_POST['strasse']) ? htmlspecialchars($_POST['strasse']) : '';
		$plz = isset($_POST['plz']) ? htmlspecialchars($_POST['plz']) : '';
		$city = isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '';
		$land =  isset($_POST['land']) ? htmlspecialchars($_POST['land']) : '';
		$vorwahl = isset($_POST['vorwahl']) ? htmlspecialchars($_POST['vorwahl']) : '';
		$telefon =  isset($_POST['telefon']) ? htmlspecialchars($_POST['telefon']) : '';
		$mobil =  isset($_POST['mobil']) ? htmlspecialchars($_POST['mobil']) : '';
		$email =  isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
		$geburtstag =  isset($_POST['geburtstag']) ? htmlspecialchars($_POST['geburtstag']) : '';
		$geschlechtid =  isset($_POST['geschlechtid']) ? htmlspecialchars($_POST['geschlechtid']) : 0;
		$firmaid =  isset($_POST['firmaid']) ? htmlspecialchars($_POST['firmaid']) : 0;
		$firma =  isset($_POST['firma']) ? htmlspecialchars($_POST['firma']) : '';
		$innungid =  isset($_POST['innungid']) ? htmlspecialchars($_POST['innungid']) : 0;
		$Notiz =  isset($_POST['Notiz']) ? htmlspecialchars($_POST['Notiz']) : '';
		$description =  isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
		$event = isset($_POST['event']) ? htmlspecialchars($_POST['event']) : '';
		$eventid = isset($_POST['eventid']) ? htmlspecialchars($_POST['eventid']) : 0;
		$cityid = isset($_POST['cityid']) ? htmlspecialchars($_POST['cityid']) : 0;
		$datefrom =  isset($_POST['datefrom']) ? htmlspecialchars($_POST['datefrom'])  : '';
		$dateto =  isset($_POST['dateto']) ? htmlspecialchars($_POST['dateto']) : '';
		$beginEvent = isset($_POST['beginEvent']) ? htmlspecialchars($_POST['beginEvent'])  : '';
		$endEvent =  isset($_POST['endEvent']) ? htmlspecialchars($_POST['endEvent']) : '';
		$public = isset($_POST['public']) ? htmlspecialchars($_POST['public']) : 1;
		$signup = isset($_POST['signup']) ? htmlspecialchars($_POST['signup']) : 0;
		$event_memberid = isset($_POST['event_memberid']) ?  htmlspecialchars($_POST['event_memberid']) : 0;
		$memberid = isset($_POST['memberid']) ?  htmlspecialchars($_POST['memberid']) : 0;
		$userid = isset($_POST['userid']) ?  htmlspecialchars($_POST['userid']) : 0;
		$ispaid = isset($_POST['isPaid']) ?  htmlspecialchars($_POST['isPaid']) : 0;
		$datesign = isset($_POST['datesign']) ?  htmlspecialchars($_POST['datesign']) : date("y-m-d");
		$active = isset($_POST['active']) ? 1 : 0;
		$input = 0;
		switch(htmlspecialchars($_POST['table'])) {
			case "member":
				//echo 'POST';
				$input = 1;
				$keys = "memberid,titel,Vorname,Vorname2,Name,Zeichen,Funktion,BegleitpersonVon,strasse,plz,city,land,vorwahl,telefon,mobil,email,geburtstag,geschlechtid,firmaid,innungid,Notiz,active,md5hash";
				$values = "NULL,'" . $titel .
				"','" . $Vorname .
				"','" . $Vorname2 .
				"','" . $Name .
				"','" . $Zeichen .
				"','" . $Funktion .
				"','" . $BegleitpersonVon .
				"','" . $strasse .
				"','" . $plz .
				"','" . $city .
				"','" . $land .
				"','" . $vorwahl.
				"','" . $telefon .
				"','" . $mobil .
				"','" . $email .
				"','" . $geburtstag .
				"'," . $geschlechtid .
				"," . $firmaid .
				"," . $innungid .
				",'" . $Notiz .
				"', 1" .
				",'" . $md5hash . "'";
				$valuePairs = "titelid='" . $titel .
				"',Vorname='" . $Vorname .
				"',Vorname2='" . $Vorname2 .
				"',Name='" . $Name .
				"',Zeichen='" . $Zeichen .
				"',Funktion='" . $Funktion .
				"',BegleitpersonVon='" . $BegleitpersonVon .
				"',strasse=''" . $strasse .
				"',plz='" . $plz .
				"',city='" . $city  .
				"',land='" . $land  .
				"',vorwahl='" . $vorwahl  .
				"',telefon='" . $telefon  .
				"',mobil='" . $mobil  .
				"',email='" . $email  .
				"',geburtstag='" . $geburtstag  .
				"',geschlechtid=" . $geschlechtid  .
				",firmaid=" . $firmaid  .
				",innungid=" . $innungid  .
				",Notiz='" . $Notiz  .
				"',active=" . $active .
				", md5hash='" . $md5hash;
				$whereString = "memberid=";
				$form = htmlspecialchars($_POST['table']);
				break;
			case "event":
				$input = 1;
				$keys = "eventid,event,description,cityid,datefrom,dateto,beginEvent,endEvent,public";
				$values = "NULL,'" . $event  .
				"','" . $description  .
				"'," . $cityid  .
				",'" . $datefrom  .
				",'" . $dateto .
				",'" . $beginEvent  .
				",'" . $endEvent ."',". $public ;
				$valuePairs = "eventid=" . $eventid.
				", event='" . $event .
				"', description='" . $description  .
				"', cityid=" . $cityid  .
				", datefrom='" . $datefrom  .
				"', dateto='" . $dateto  .
				"', beginEvent='" . $beginEvent  .
				"', endEvent='" . $endEvent  .
				"', public=" . $public;
				$whereString = "eventid=";
				$form = htmlspecialchars($_POST['table']);
				break;
			case "firma":
				$input = 1;
				$keys = "firmaid,firma,strasse,plz,city,land,innungid";
				$values = "NULL,'" . $firma  .
				"','" . $strasse .
				"','" . $plz  .
				"','" . $city  .
				"','"  . $land  .
				"'," . $innungid;
				$valuePairs = "firmaid=" . $firmaid .
				", firma='" . $firma .
				"', strasse='" . $strasse .
				"', plz='" . $plz .
				"', city='" . $city  .
				"', land='" . $land  .
				"', innungid=" . $innungid;
				$whereString = "firmaid=";
				$form = htmlspecialchars($_POST['table']);
				break;
			case "event_member":
					$input = 1;
					$keys = "event_memberid,eventid,memberid,signup,isPaid,userid,datesign,md5hash";

					$values = "NULL".
					"," . $eventid .
					"," . $memberid .
					",1" .
					",0" .
					"," . $userid .
					",'" . $datesign .
					"','" . $md5hash."'";

					$valuePairs = "event_memberid=" . $event_memberid .
					", eventid=" . $eventid .
					", memberid=" . $memberid .
					", signup=" . $signup .
					", isPaid=" . $ispaid .
					", userid=" . $userid .
					", datesign='" . $datesign .
					"', md5hash='" . $md5hash ."'";
					$whereString = "event_memberid=";
					$form = htmlspecialchars($_POST['table']);
					break;
				case "user":
							$input = 3;
							$form = htmlspecialchars($_POST['table']);
							break;
		}
		if($input === 1 || $input === 3) {
			$curTable = $prefix . htmlspecialchars($_POST['table']);
		}
	}

	if(isset($_POST['table2'])) {
		$input = 2;
		switch(htmlspecialchars($_POST['table2'])) {
			case "event_member":
				$input = 2;
				$keys2 = "event_memberid,eventid,memberid,signup,userid,datesign,md5hash";
				$values2 = "NULL".
				"," . $eventid .
				",0" .
				",0" .
				",0" .
				",''" .
				",'" . $md5hash."'";
				$valuePairs2 = "event_memberid=" . $event_memberid .
				", eventid=" . $eventid .
				", memberid=" . $memberid .
				", signup=" . $signup .
				", isPaid=" . $ispaid .
				", userid=" . $userid .
				", datesign='" . $datesign .
				"', md5hash='" . $md5hash ."'";
				$whereString2 = "event_memberid=";
				$form2 = htmlspecialchars($_POST['table2']);
				break;

		}
		if($input === 2) {
			$curTable2 = $prefix . htmlspecialchars($_POST['table2']);
		}
	}

	// Connect to Database
	define('HOST',$MySQLHost);
	define('USER',$MySQLUsername);
	define('PASS',$MySQLPassword);
	define('DB',$MySQLDB);
	$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');

	// Set Charset to UTF8
	mysqli_set_charset($con, "utf8");


	$id = 0;

	if($input == 3) {
		$userid = 0;
		$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'kritzelbitz';
		$strSQL = "SELECT userid,passwd FROM " . $curTable . " WHERE username='". $username . "'";
		//var_dump($username);
		// update Data
		$queryUser = mysqli_query($con,$strSQL);
		$outputUser = [];
		$passwd = isset($_POST['passwd']) ? md5(htmlspecialchars($_POST['passwd'])) : 'kritzelbitz';

		while($rowUser = $queryUser->fetch_assoc()) {
			//var_dump($passwd);
			//var_dump($rowUser['passwd']);
			if($rowUser['passwd'] == $passwd){
				$userid = $rowUser['userid'];
			};
		}
		//var_dump($userid);

		$_SESSION['userid'] = $userid;
		if($userid == 0) {
			header("Location: " . "login.php");
		} else {
			header("Location: " . "checkInn.php");
		}
	}


	if($_POST and isset($_POST[$form .'id'])) {
		$nameid = $form . 'id';
		//var_dump($nameid);
		$id = isset($_POST[$form . 'id']) ? htmlspecialchars($_POST[$form . 'id']) : $id;
	}

	if($id > 0) {
		$strSQL = "UPDATE " . $curTable . " SET " . $valuePairs . " WHERE " . $whereString . $id;
		//var_dump($strSQL);
		// update Data
		$query = mysqli_query($con,$strSQL);


	} else {
		if(isset($curTable) && $input < 3){
			$strSQL = "INSERT INTO " . $curTable . "(" . $keys . ") values (" . $values. ")";

			//var_dump($strSQL);

			// write Data
			$query = mysqli_query($con,$strSQL);


			$memberid = mysqli_insert_id($con);
			if(isset($_POST['table2'])) {
				$strSQL2 = "INSERT INTO " . $curTable2 . "(" . $keys2 . ") values (" . $values2. ")";

				//var_dump($strSQL2);

				// write Data
				$query = mysqli_query($con,$strSQL2);


				$event_memberid = mysqli_insert_id($con);

				$strSQL = "UPDATE " . $curTable2 . " SET memberid=" . $memberid . " WHERE ". $whereString2 . $event_memberid;
				//var_dump($strSQL);
				// update Data
				$query = mysqli_query($con,$strSQL);
			}
		} else {
			header("Location: " . "login.php");
		}



	}
	// Variabeln



	if($form === "member") {
	  if(isset($memberid) && $memberid > 0) {
			$whereString = createWhereMd5Hash($_POST,$md5hash,$memberid);

			$strSQL = "SELECT eventid, event, datefrom, beginEvent, countMax FROM event WHERE eventid=". $eventid ;
			//var_dump($strSQL);
			// update Data
			$queryEvent = mysqli_query($con,$strSQL);
			$outputEvent = [];

			while($rowEvent = $queryEvent->fetch_assoc()) {
				//var_dump($passwd);
				//var_dump($rowUser['passwd']);
				$aDateFrom = ['','',''];
				$maxCount = 0;
				$beginEvent = '';
				if($rowEvent['eventid'] == $eventid){
					$event = $rowEvent['event'];
					$aDateFrom = explode("-",$rowEvent['datefrom']);
					$beginEvent = $rowEvent['beginEvent'];
					$maxCount = $rowEvent['countMax'];
				}
			}

			$strSQL = "SELECT event_memberid FROM event_member WHERE eventid=". $eventid ;
			//var_dump($strSQL);
			// update Data
			$queryMember = mysqli_query($con,$strSQL);
			$outputMember = [];
			$count = 0;
			while($rowMember = $queryMember->fetch_assoc()) {
				$count++;
			}


			$header  = "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html; charset=utf-8\r\n";

			$header .= 'From: ' . $emailHWKFrom  . "\r\n";
			$Funktion = $Funktion ? "Funktion: " . $Funktion . ",<br>" : '' ;
			$social = $telefon ? $telefon . ",<br>" : '' ;
			$social .= $mobil ? $mobil . ",<br>" : '' ;
			$social .= $email ? $email . ",<br>" : '' ;
			$Notiz = $Notiz ? "Notiz: " . $Notiz . ",<br>" : '' ;
			$BegleitpersonVon = $BegleitpersonVon ? "Begleitperson von " . $BegleitpersonVon . ",<br>" : '' ;

			mail(
    			$emailHWK,
	    		"Anmeldung Nr. " . $count ." - ". $Vorname . " ". $Name . " - " . $event . " (" . $aDateFrom[2] . "." . $aDateFrom[1] . "." . $aDateFrom[0] . " " . $beginEvent .")"  ,
    			$maxCount . " Personen maximal,<br> ". $Vorname . " ". $Name . ",<br>" . $social . $Funktion  . $BegleitpersonVon . $strasse . "<br>" . $plz. " "  .$city ."<br>" . $Notiz . "<a href='" . $destUrl . "form_". $form . "_qr.php?" . $whereString . "'>zur Anmeldung</a>",
    			$header,
    			""
			);
			header("Location: " . "form_". $form . "_qr.php?" . $whereString);
		}

	}elseif($form === "event_member") {
			header("Location: " . "form_". $form . "_new.php?eventid=" . $eventid);
	}elseif($form === "user") {
			if(isset($_SESSION['userid']) && $_SESSION['userid'] > 0) {
				header("Location: " . "checkInn.php?eventid=" . $eventid);
			} else {
				header("Location: " . "login.php");
			}

	}elseif($form !== "") {

		header("Location: " . "form_". $form . "_new.php?" . $whereString  . $id);
	}
mysqli_close($con);
function createWhereMd5Hash($POST_,$md5Hash_,$id_){
	return "meid=" . $md5Hash_ . "&m=" . $id_ . "&eid=" . $POST_['eventid'];
}

function createMd5Hash($POST_,$aMd5Hash_){
	$strMd5Hash = '';
	foreach($_POST as $key=>$value) {
		if(array_search($key, $aMd5Hash_, true))
  		$strMd5Hash .= $value;
	}

	return md5($strMd5Hash);
}

?>
