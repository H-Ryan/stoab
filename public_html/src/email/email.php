<?php
require 'email/Emails.php';
$connection = mysqli_connect(HOST, USER, PASS, DATABASE);
if ($connection->connect_errno) {
	printf("Failed to connect to MySQL: %s\n", $connection->connect_error);
	exit();
}

if (!mysqli_ping($connection))
{
	die("Error: ". mysqli_error($connection));
}

//Remove $_COOKIE elements from $_REQUEST.

if (count($_COOKIE)) {
    foreach (array_keys($_COOKIE) as $value) {
        unset($_REQUEST[$value]);
    }
}

if (!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']))) {
    $errors[] = "You must enable referrer logging to use the form";
}

if(isset($_POST['Kontaktperson']) && isset($_POST['Epostadress']) && isset($_POST['Klienttelefon']) 
	&& isset($_POST['Tolkplats']) && isset($_POST['TolkPostnummer']) && isset($_POST['TolkOrt']) && isset($_POST['Klient'])
	&& isset($_POST['Sprak']) && isset($_POST['Datum']) && isset($_POST['Starttid']) && isset($_POST['Starttid1'])
	&& isset($_POST['Sluttid']) && isset($_POST['Sluttid1']) && isset($_POST['Bestallare']) && isset($_POST['Foretagsnamn'])
	&& isset($_POST['Adress']) && isset($_POST['Postnummer']) && isset($_POST['Ort'])
	&& isset($_POST['Typ'])) 
{
    $_GLOBALS['email'] = $_POST['Epostadress'];
	$kontakt_person = $_POST['Kontaktperson'];
	$epost = $_POST['Epostadress'];
	$klient_telefon = $_POST['Klienttelefon'];
	$tolk_plats = $_POST['Tolkplats'];
	$tolk_postnummer = $_POST['TolkPostnummer'];
	$tolk_ort = $_POST['TolkOrt'];
	$klient = $_POST['Klient'];
	$sprak = $_POST['Sprak'];
	$datum = $_POST['Datum'];
	$starttid = $_POST['Starttid'].":".$_POST['Starttid1'];
	$sluttid = $_POST['Sluttid'].":".$_POST['Sluttid1'];
	$bestallare = $_POST['Bestallare'];
	$foretagsnamn = $_POST['Foretagsnamn'];
	$adress = $_POST['Adress'];
	$postnummer = $_POST['Postnummer'];
	$ort = $_POST['Ort'];
	$kommentar = "";
	if(isset($_POST['Ovrigt'])) { $kommentar = $_POST['Ovrigt']; }
	$typ_tolkning = $_POST['Typ'];
	//search is already exist
	$query = "SELECT id FROM contactPerson WHERE name = '".$kontakt_person."' AND email = '".$epost."' AND telephone = '".$klient_telefon."' ".
		"AND address = '".$tolk_plats."' AND post_number = ".$tolk_postnummer." AND city = '".$tolk_ort."';";
	$result = mysqli_query($connection, $query);
	$GLOBALS['contact_id'] = 0;
	if ($result) {
		if (mysqli_num_rows($result) == 1) {
				$row = mysqli_fetch_array($result);
				$GLOBALS['contact_id'] = (int) $row['id'];
		} else if (mysqli_num_rows($result) == 0) {
			$query = "INSERT INTO contactPerson (id, name, email, telephone, address, post_number, city)".
				"VALUES (NULL, '".$kontakt_person."', '".$epost."', '".$klient_telefon."', '".$tolk_plats."', ".$tolk_postnummer.", '".$tolk_ort."');";
			$result = mysqli_query($connection, $query);
			if($result) {
				$query = "SELECT id FROM contactPerson WHERE name = '".$kontakt_person."' AND email = '".$epost."' AND telephone = '".$klient_telefon."' ".
					"AND address = '".$tolk_plats."' AND post_number = ".$tolk_postnummer." AND city = '".$tolk_ort."';";
				$result = mysqli_query($connection, $query);
				if ($result) {
					if (mysqli_num_rows($result) == 1) {
						$row = mysqli_fetch_array($result);
						$GLOBALS['contact_id'] = (int) $row['id'];
					}
				}
			}
		}
	}
	//get id
	$query = "SELECT id FROM organization WHERE boss = '".$bestallare."' AND name = '".$foretagsnamn."' AND address='".$adress."' AND".
		" post_number= ".$postnummer." AND city='".$ort."';";
	$result = mysqli_query($connection, $query);
	$GLOBALS['organization_id'] = 0;
	if ($result) {
		if (mysqli_num_rows($result) == 1) {
				$row = mysqli_fetch_array($result);
				$GLOBALS['organization_id'] = (int) $row['id'];
		} else if (mysqli_num_rows($result) == 0) {
			$query = "INSERT INTO organization (id, boss, name, address, post_number, city)".
				"VALUES (NULL, '".$bestallare."', '".$foretagsnamn."', '".$adress."', '".$postnummer."', '".$ort."');";
			$result = mysqli_query($connection, $query);
			if ($result) {
				$query = "SELECT id FROM organization WHERE boss = '".$bestallare."' AND name = '".$foretagsnamn."' AND address='".$adress."' AND".
					" post_number= ".$postnummer." AND city='".$ort."';";
				$result = mysqli_query($connection, $query);
				if ($result) {
					if (mysqli_num_rows($result) == 1) {
							$row = mysqli_fetch_array($result);
							$GLOBALS['organization_id'] = (int) $row['id'];
					}
				}
			}
		}
	}
	
	$GLOBALS['order_number'] = genOrderNumber();
	
	while(!checkForDuplicateNumber($connection,$GLOBALS['order_number'])){
	    $GLOBALS['order_number'] = genOrderNumber();
	}
	
	
	$query = "INSERT INTO orders (id, contactPerson_id,klient , language, date, start_time, end_time, organization_id, comment, interpreter_id, type, state, date_submit, order_number)".
		" VALUES (NULL, '".$GLOBALS['contact_id']."', '".$klient."', '".$sprak."', '".$datum."', '".$starttid."', '".$sluttid."', '".$GLOBALS['organization_id']."', '".$kommentar."', NULL, '".$typ_tolkning."', 'X', '".date('Y-m-d')."', '".$GLOBALS['order_number']."');";
	$result = mysqli_query($connection, $query);
	if(!$result){
		die(mysqli_error($connection));
	}
	
	if($typ_tolkning == 'KT') {
		$typ_tolkning = "Kontakttolkning";
	} else {
		$typ_tolkning = "Telefontolkning";
	}
	
	//TODO email both the company and customer
	$continue = "/";
	
	$message_to_company = "<!DOCTYPE HTML><html>
	<body>
		<h2 style='text-align: center; margin-top: 5%;'>Beställning:</h2>
		<h2 style='text-align: center; margin-top: 5%;'>Uppdrag Nummer:".$GLOBALS['order_number']."</h2>
		<table style='width: 80%;
				margin-left: 10%;
				margin-right: 10%;
				text-align: center;
				font-family: verdana, arial, sans-serif;
				font-size: 14px;
				color: #333333;
				border-width: 1px;
				border-radius: 5px;
				border-color: #999999;' cellpadding='10'>
			<thead>
				<th style='background-color: #599CFF;
				border-width: 1px;
				font-size: 18px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;
				border-radius: inherit; border: 1px solid black; '>Kontaktperson:</th>
				<th style='background-color: #599CFF;
				border-width: 1px;
				font-size: 18px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;
				border-radius: inherit; border: 1px solid black; '>Uppdrag:</th>
				<th style='background-color: #599CFF;
				border-width: 1px;
				font-size: 18px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;
				border-radius: inherit; border: 1px solid black; '>Fakturering:</th>
			</thead>
			<tbody>
				<tr style='background-color: #d4e3e5;
				border-radius: 5px;'>
					<td style='border-width: 1px;
					padding: 8px;
					border-style: solid;
					border-color: #a9c6c9;'>
						<div style='vertical-align:top; margin-top:5%;'>
							<label style='font-weight:bold;'>Kontaktperson:</label>
							<p>$kontakt_person</p>
							<br />
							<label style='font-weight:bold;'>Epostadress:</label>
							<p>$epost</p>
							<br />
							<label style='font-weight:bold;'>Klienttelefon:</label>
							<p>$klient_telefon</p>
							<br />
							<label style='font-weight:bold;'>Tolkplats:</label>
							<p>$tolk_plats</p>
							<br />
							<label style='font-weight:bold;'>TolkPostnummer:</label>
							<p>$tolk_postnummer</p>
							<br />
							<label style='font-weight:bold;'>TolkOrt:</label>
							<p>$tolk_ort</p>
						</div>
					</td>
					<td style='border-width: 1px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;'>
						<div style='vertical-align:top; margin-top:5%;'>
							<label style='font-weight:bold;'>Klient: </label>
							<p>$klient</p>
							<br />
							<label style='font-weight:bold;'>Språk: </label>
							<p>$sprak</p>
							<br />
							<label style='font-weight:bold;'>Typ: </label>
							<p>$typ_tolkning</p>
							<br />
							<label style='font-weight:bold;'>Datum: </label>
							<p>$datum</p>
							<br />
							<label style='font-weight:bold;'>Starttid: </label>
							<p>$starttid</p>
							<br />
							<label style='font-weight:bold;'>Sluttid: </label>
							<p>$sluttid</p>
						</div>
					</td>
					<td style='border-width: 1px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;'>
						<div style='vertical-align:top; margin-top:5%;'>
							<label style='font-weight:bold;'>Bestallare: </label>
							<p>$bestallare</p>
							<br />
							<label style='font-weight:bold;'>Foretagsnamn: </label>
							<p>$foretagsnamn</p>
							<br />
							<label style='font-weight:bold;'>Adress: </label>
							<p>$adress</p>
							<br />
							<label style='font-weight:bold;'>Postnummer: </label>
							<p>$postnummer</p>
							<br />
							<label style='font-weight:bold;'>Ort: </label>
							<p>$ort</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<div style='width:70%;
				margin-top:3%;
				margin-bottom:1.5%;
				margin-left:15%;
				background-color: #d4e3e5;
				border-width: 1px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;'>
			<label style='font-weight:bold;'>Kommentar:</label>
			<p>$kommentar</p>
		</div>
		<hr />
	</body>
	
</html>";
	$message_to_customer = "<!DOCTYPE HTML><html>
	<body>
		<p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
		Tack för ditt beställningen!<br /><br />
        I detta bekräftelse behöver vi komplettera uppgifterna för beställningen.<br />
        Vi ska kunna ta hand om ordet på rätt sätt och kunna skapa uppdragstagare<br />
        till er vi kommer och svara er så fort så möjlighet.
        </p>
		<hr style='width: 80%;
				margin-left: 10%;' />
		<h2 style='text-align: center; margin-top: 5%;'>Beställning:</h2>
		<h2 style='text-align: center; margin-top: 5%;'>Uppdrag Nummer:".$GLOBALS['order_number']."</h2>
		<table style='width: 80%;
				margin-left: 10%;
				margin-right: 10%;
				text-align: center;
				font-family: verdana, arial, sans-serif;
				font-size: 14px;
				color: #333333;
				border-width: 1px;
				border-radius: 5px;
				border-color: #999999;' cellpadding='10'>
			<thead>
				<th style='background-color: #599CFF;
				border-width: 1px;
				font-size: 18px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;
				border-radius: inherit; border: 1px solid black; '>Kontaktperson:</th>
				<th style='background-color: #599CFF;
				border-width: 1px;
				font-size: 18px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;
				border-radius: inherit; border: 1px solid black; '>Uppdrag:</th>
				<th style='background-color: #599CFF;
				border-width: 1px;
				font-size: 18px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;
				border-radius: inherit; border: 1px solid black; '>Fakturering:</th>
			</thead>
			<tbody>
				<tr style='background-color: #d4e3e5;
				border-radius: 5px;'>
					<td style='border-width: 1px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;'>
						<div style='vertical-align:top; margin-top:5%;'>
							<label style='font-weight:bold;'>Kontaktperson:</label>
							<p>$kontakt_person</p>
							<br />
							<label style='font-weight:bold;'>Epostadress:</label>
							<p>$epost</p>
							<br />
							<label style='font-weight:bold;'>Klienttelefon:</label>
							<p>$klient_telefon</p>
							<br />
							<label style='font-weight:bold;'>Tolkplats:</label>
							<p>$tolk_plats</p>
							<br />
							<label style='font-weight:bold;'>TolkPostnummer:</label>
							<p>$tolk_postnummer</p>
							<br />
							<label style='font-weight:bold;'>TolkOrt:</label>
							<p>$tolk_ort</p>
						</div>
					</td>
					<td style='border-width: 1px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;'>
						<div style='vertical-align:top; margin-top:5%;'>
							<label style='font-weight:bold;'>Klient: </label>
							<p>$klient</p>
							<br />
							<label style='font-weight:bold;'>Språk: </label>
							<p>$sprak</p>
							<br />
							<label style='font-weight:bold;'>Typ: </label>
							<p>$typ_tolkning</p>
							<br />
							<label style='font-weight:bold;'>Datum: </label>
							<p>$datum</p>
							<br />
							<label style='font-weight:bold;'>Starttid: </label>
							<p>$starttid</p>
							<br />
							<label style='font-weight:bold;'>Sluttid: </label>
							<p>$sluttid</p>
						</div>
					</td>
					<td style='border-width: 1px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;'>
						<div style='vertical-align:top; margin-top:5%;'>
							<label style='font-weight:bold;'>Bestallare: </label>
							<p>$bestallare</p>
							<br />
							<label style='font-weight:bold;'>Foretagsnamn: </label>
							<p>$foretagsnamn</p>
							<br />
							<label style='font-weight:bold;'>Adress: </label>
							<p>$adress</p>
							<br />
							<label style='font-weight:bold;'>Postnummer: </label>
							<p>$postnummer</p>
							<br />
							<label style='font-weight:bold;'>Ort: </label>
							<p>$ort</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<div style='width:70%;
				margin-top:3%;
				margin-bottom:1.5%;
				margin-left:15%;
				background-color: #d4e3e5;
				border-width: 1px;
				padding: 8px;
				border-style: solid;
				border-color: #a9c6c9;'>
			<label style='font-weight:bold;'>Kommentar:</label>
			<p>$kommentar</p>
		</div>
		<hr style='width: 80%;
				margin-left: 10%;' />
		<div>
			<p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
				Om informationen ovan är felaktig eller om du vill ändra något, vänligen kontakta oss.
			</p>
		</div>
		<hr style='width: 80%;
				margin-left: 10%;' />
		<footer style='margin-left: 10%; width:80%'>
			<h2>STÖ Sarvari Tolkning och Översättning AB</h2>
			<p><label style='font-weight:bold;'>ADRESS:</label> Transportgatan 4-5, 281 52 Hässleholm</p>
			<p><label style='font-weight:bold;'>EPOST:</label> <a href='mailto:info@tolkjanst.se'>info@tolkjanst.se</a></p>
			<p><label style='font-weight:bold;'>HEMSIDA:</label> <a href='http://www.tolkjanst.se'>www.tolkjanst.se</a></p>
			<p><label style='font-weight:bold;'>TELEFON:</label> 0451-742055</p>
			<p><label style='font-weight:bold;'>ORGANISATIONSNR:</label> 556951-0802</p>
	  
		</footer>
	</body>
	
</html>";
    $query = "SELECT id FROM contactPerson WHERE name = '".$kontakt_person."' AND email = '".$epost."' AND telephone = '".$klient_telefon."' "."AND address = '".$tolk_plats."' AND post_number = ".$tolk_postnummer." AND city = '".$tolk_ort."';";
	$result = mysqli_query($connection, $query);
	
    $company_email =' <bokning@tolktjanst.se>';//bokning@tolktjanst.se
	$customer_email = "<".$_POST['Epostadress'].">" ;
	
	$from_email = "STÖ AB <bokning@tolktjanst.se>";
	
	$subject = "Tolk beställning från hemsida.";//subject for company's email
	$subject2 = "STÖ AB - Order.";//subject for customer's email
	
	if(send_email($customer_email, $from_email , $subject2, $message_to_customer) &&
	    send_email($company_email, $from_email, $subject, $message_to_company)){
		
	} else {
		die('We are sorry, but there appears to be a problem with our e-mail server. Please try again later.');
	}
	
} else {
	die('We are sorry, but there appears to be a problem with the form you submitted.');	
}
    function checkForDuplicateNumber($connection, $order_number){
	    $query = "SELECT id FROM orders WHERE order_number='".$order_number."';";
    	$result = mysqli_query($connection, $query);
    	if ($result) {
    		if (mysqli_num_rows($result) == 1) {
    		    return false;	
    		} else {
    		    return true;
    		}
    	}
	}
    function genOrderNumber() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $randomString = '';
        for ($i = 0; $i < 2; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        for ($i = 0; $i < 4; $i++) {
            $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        return $randomString;
    }
//Close mysql connection
mysqli_close($connection);
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
		<title>Beställ</title>
		<style type="text/css"> 
			<!--
			BODY { color: #333333; font-size: 12px; font-family: verdana,Tahoma, Helvetica, sans-serif }
			TD { color: #333333; font-size: 12px; font-family: verdana,Tahoma, Helvetica, sans-serif }
			.sefid { color: #cccccc; font-size: 12px; font-family: verdana,arial,Tahoma, Helvetica, sans-serif }
			.siah { color: #000000; font-size: 14px; font-family: verdana,arial,Tahoma, Helvetica, sans-serif }
			.titel{ color: #999999; font-size: 27px; font-family: times, Helvetica, sans-serif; font-weight: bold }
			.lank { color: #353435}
			H2 { color:#999999; font-size: 18px; font-family: Arial,Tahoma, Helvetica, sans-serif }
			OL { color: #cc0000; font-size: 13px; font-family: arial,Tahoma, Helvetica, sans-serif; font-weight: bold; }
			.lank { color: #353435}
			H2 { color:#0A8A99; font-size: 18px; font-family: Arial,Tahoma, Helvetica, sans-serif }
			a:hover {color: #000000; background: #cccccc;}
			a {text-decoration: none;} 

			 -->
			 .button {
			  border: 1px solid #C9C4BA; color: white; background: #127DCF; cursor: pointer; font: 10px verdana; padding: 10px 20px; text-decoration: none;
				-khtml-border-radius: 5px; -moz-border-radius: 5px; -opera-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; cursor= pointer; margin-top: 15px;
			}
			.button:hover { border-color: #DFDCD6; background-color:#33CCFF; text-decoration: none;}
		</style>
	</head>
	<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<div align=center>
			<table width="450" height="300" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align=top>
						<br /><br />
						<blockquote>
						<br /><br />
						<br /><br />
						<h3>Tack för beställningen! - Tolktjänst </h3>
						<br />
						<p><a class="button" href="<?php print $continue;?>" target="_top">Startsida</a>&nbsp;</p>
						<p></p>
					</td>
				</tr>
			</table>
			<br><br>
			<br><br>
		</div>
	</body>
</html>