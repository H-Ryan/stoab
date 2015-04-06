<?php
ob_start();
require 'dbconnector.php';
require 'functions.php';
require 'db-config.php';
require '../mail-test/mailing.php';

if (isset($_POST['tolk-name']) && isset($_POST['tolk-email']) && isset($_POST['tolk-telephone']) && isset($_POST['tolk-city'])) {
	$connection = mysqli_connect(HOST, USER, PASS, DATABASE) or die("Could't connect to MySQL:" . mysqli_error());
	if (mysqli_connect_errno()) {
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}

	if (!mysqli_ping($connection)) {
		die("Error: " . mysqli_error($con));
	}
	checkIfTolkExists($connection);
	$tolk_email = $_POST['tolk-email'];
	$customer_email = $_POST['customer-email'];
	$tolk_subject = "STÖ AB - Uppdrag.";
	$customer_subject = "STÖ AB - Uppdragsbekräftelse.";
	
	//Construct the message
	$order = getDetails($connection, "orders", $_POST['order-id']);
	$contactPerson = getDetails($connection, "contactPerson", $order['contactPerson_id']);
	$organization = getDetails($connection, "organization", $order['organization_id']);
	if($order['type'] == 'KT') {
		$order['type'] = "Kontakttolkning";
	} else {
		$order['type'] = "Telefontolkning";
	}
	
	$message_to_tolk = "<!DOCTYPE HTML><html>
	<head><meta http-equiv='Content-Type' content='text/html' charset='utf-8'></head>
	<body>
		<p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
			Hej.<br />Du har tilldelats ett tolkuppdrag. Detaljer om tolkuppdraget finns nedan.</p>
		<hr style='width: 80%;
				margin-left: 10%;' />
		<h2 style='text-align: center; margin-top: 5%;'>Uppdrag</h2>
		<h2 style='text-align: center; margin-top: 5%;'>Uppdrag Nummer:".$order['order_number']."</h2>
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
				<tr>
					<th  style='background-color: #599CFF;
					border-width: 1px;
					font-size: 18px;
					padding: 8px;
					border-style: solid;
					border-color: #a9c6c9;
					border-radius: inherit; border: 1px solid black;'>Uppdrag</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Datum:</label> ".$order['date']."</p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Starttid:</label> ".$order['start_time']."<p> <label style='font-weight:bold;'>Sluttid:</label> ".$order['end_time']."</p></p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Gatuadress:</label> ".$contactPerson['address']."</p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Postnummer:</label> ".$contactPerson['post_number']."</p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Ort:</label> ".$contactPerson['city']."</p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Typ av uppdrag:</label> ".$order['type']."</p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Språk:</label> ".$order['language']."</p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Klient:</label> ".$order['klient']."</p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Kontaktperson:</label> ".$contactPerson['name']."</p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Telefonnr:</label> ".$contactPerson['telephone']."</p>
					</td>
				</tr>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Epostadress:</label> ".$contactPerson['email']."</p>
					</td>
				</tr>
			</tbody>
		</table>
		<hr style='width: 80%;
				margin-left: 10%;' />
		<div>
			<p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
				Om du har några frågor eller funderingar kan du kontakta oss.
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
	$message_to_customer = "<!DOCTYPE HTML><html>
	<head><meta http-equiv='Content-Type' content='text/html' charset='utf-8'></head>
	<body>
		<p style='font-size: 16px; margin-left: 10%; margin-top: 2.5%; margin-bottom:2.5%;'>
			Ni har framgångsrikt tilldelats en tolk för den aktuella tolkuppdraget.
			<br />
			Tolkens kontaktuppgifter finns nedan tillsammans med din beställning.</p>
		<hr style='width: 80%;
				margin-left: 10%;' />
		<h2 style='text-align: center; margin-top: 5%;'>Tolkuppdrag</h2>
		<h2 style='text-align: center; margin-top: 5%;'>Uppdrag Nummer:".$order['order_number']."</h2>
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
				<tr>
					<th  style='background-color: #599CFF;
					border-width: 1px;
					font-size: 18px;
					padding: 8px;
					border-style: solid;
					border-color: #a9c6c9;
					border-radius: inherit; border: 1px solid black;'>Uppdrag</th>
					<th  style='background-color: #599CFF;
						border-width: 1px;
						font-size: 18px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;
						border-radius: inherit; border: 1px solid black;'>Tolk</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Datum:</label> ".$order['date']."</p>
						<p><label style='font-weight:bold;'>Starttid:</label> ".$order['start_time']."<p> <label style='font-weight:bold;'>Sluttid:</label> ".$order['end_time']."</p></p>
						<p><label style='font-weight:bold;'>Gatuadress:</label> ".$contactPerson['address']."</p>
						<p><label style='font-weight:bold;'>Postnummer:</label> ".$contactPerson['post_number']."</p>
						<p><label style='font-weight:bold;'>Ort:</label> ".$contactPerson['city']."</p>
						<p><label style='font-weight:bold;'>Typ av uppdrag:</label> ".$order['type']."</p>
						<p><label style='font-weight:bold;'>Språk:</label> ".$order['language']."</p>
						<p><label style='font-weight:bold;'>Klient:</label> ".$order['klient']."</p>
						<p><label style='font-weight:bold;'>Kontaktperson:</label> ".$contactPerson['name']."</p>
						<p><label style='font-weight:bold;'>Telefonnr:</label> ".$contactPerson['telephone']."</p>
						<p><label style='font-weight:bold;'>Epostadress:</label> ".$contactPerson['email']."</p>
					</td>
					<td style='background-color: #d4e3e5;
						border-width: 1px;
						padding: 8px;
						border-style: solid;
						border-color: #a9c6c9;'>
						<p><label style='font-weight:bold;'>Namn:</label> ".$_POST['tolk-name']."</p>
						<p><label style='font-weight:bold;'>Telefonnr:</label> ".$_POST['tolk-telephone']."</p>
						<p><label style='font-weight:bold;'>Epostadress:</label> ".$_POST['tolk-email']."</p>
						<p><label style='font-weight:bold;'>Hemort:</label> ".$_POST['tolk-city']."</p>
					</td>
				</tr>
			</tbody>
		</table>
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
    $from_email = "STÖ AB <bestallning@tolktjanst.se>";
	if (send_email(' <'.$tolk_email.'>',$from_email , $tolk_subject, $message_to_tolk) 
		&& send_email(' <'.$customer_email.'>',$from_email , $customer_subject, $message_to_customer)) {	
	} else {
		die('We are sorry, but there appears to be a problem with our e-mail server. Please try again later.');
	}

}

function getDetails($connection, $table, $id){
	$result = mysqli_query($connection, "SELECT * FROM $table WHERE id='$id';");
	if (!$result)
		die("Database access failed: " . mysqli_error($connection));
	else {
		if (mysqli_num_rows($result) == 1) {
			return mysqli_fetch_array($result);
		}else {
			die("Database accessled: " . mysqli_error($connection));
		}
	}
}

function checkIfTolkExists($connection) {
	$result = mysqli_query($connection, "SELECT id FROM interpreter WHERE name='" .
		 $_POST['tolk-name'] . "' AND email='" . 
		 $_POST['tolk-email'] . "' AND telephone='" . 
		 $_POST['tolk-telephone'] . "' AND city='" . 
		 $_POST['tolk-city'] . "';");
	if (!$result)
		die("Database access failed: " . mysqli_error($connection));
	else {
		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_array($result);
			$GLOBALS['tolk_id'] = (int)$row['id'];
		} else if (mysqli_num_rows($result) == 0) {
			$query = "INSERT INTO interpreter (id, name, email, telephone, city)" . " VALUES (NULL, '".
				$_POST['tolk-name']."', '".
				$_POST['tolk-email']."', '".
				$_POST['tolk-telephone']."', '".
				$_POST['tolk-city']."');";
			$result = mysqli_query($connection, $query);
			if ($result) {
				$query = "SELECT id FROM interpreter WHERE name='" .
					$_POST['tolk-name'] . "' AND email='" .
					$_POST['tolk-email'] . "' AND telephone='" .
					$_POST['tolk-telephone'] . "' AND city='" .
					$_POST['tolk-city'] . "';";
				$result = mysqli_query($connection, $query);
				if ($result) {
					if (mysqli_num_rows($result) == 1) {
						$row = mysqli_fetch_array($result);
						$GLOBALS['tolk_id'] = (int)$row['id'];
					}
				}
			}
		}
		
		$result = mysqli_query($connection, "UPDATE orders SET interpreter_id=".
			$GLOBALS['tolk_id'].", state='O' WHERE id='".
			$_POST['order-id']."'");
		
		if (!$result)
			die("Database access failed: " . mysqli_error($connection));
		
	}
}
mysqli_close($connection);

header('Location: orders.php');
exit ;
?>