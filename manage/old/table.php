<?php
require 'dbconnector.php';
require 'functions.php';
require 'db-config.php';
echo <<<AOT
    <!DOCTYPE HTML>
    <html>
    <head>
    <title>table</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
AOT;

if (isset($_POST['date']) && $_POST['date'] != '') {
	$GLOBALS['query'] = getRequestedQuery("date");
	getTable();
} elseif (isset($_POST['language']) && $_POST['language'] != '') {
	$GLOBALS['query'] = getRequestedQuery("language");
	getTable();
} elseif (isset($_POST['c-name']) && $_POST['c-name'] != '') {
	$GLOBALS['query'] = getRequestedQuery("name");
	getTable();
} elseif (isset($_POST['typ']) && $_POST['typ'] != '') {
	$GLOBALS['query'] = getRequestedQuery("type");
	getTable();
} elseif (isset($_POST['type'])) {
	if ($_POST['type'] == 'all') {
		$GLOBALS['query'] = "SELECT * FROM orders ORDER BY id DESC;";
		getTable();
	}
} elseif(isset($_POST['number']) && $_POST['number'] != '') {
    $GLOBALS['query'] = getRequestedQuery("number");
	getTable();
} else {
	echo "<p id='error'>The fields are empty!</p><br /><br />";
}
function getRequestedQuery($col)
{
	if($col == "date"){
		if (isset($_POST['c-name']) && isset($_POST['language']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND language='"
				.$_POST['language']. "' AND type='"
				.$_POST['typ'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (!isset($_POST['c-name']) && isset($_POST['language']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND language='"
				.$_POST['language']. "' AND type='"
				.$_POST['typ'] . "' ORDER BY id DESC;";
		} elseif (isset($_POST['c-name']) && !isset($_POST['language']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND type='"
				.$_POST['typ'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (isset($_POST['c-name']) && isset($_POST['language']) && !isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND language='"
				.$_POST['language']. "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (!isset($_POST['c-name']) && !isset($_POST['language']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND type='"
				.$_POST['typ'] . "' ORDER BY id DESC;";
		} elseif (!isset($_POST['c-name']) && isset($_POST['language']) && !isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND language='"
				.$_POST['language']. "' ORDER BY id DESC;";
		} elseif (isset($_POST['c-name']) && !isset($_POST['language']) && !isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} else {
			return "SELECT * FROM orders WHERE date_submit='" .$_POST['date'] . "' ORDER BY id DESC;";
		}
	} elseif ($col == "language") {
		if (!isset($_POST['date']) && isset($_POST['c-name']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE language='"
				.$_POST['language']. "' AND type='"
				.$_POST['typ'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (isset($_POST['date']) && !isset($_POST['c-name']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND language='"
				.$_POST['language']. "' AND type='"
				.$_POST['typ'] . "' ORDER BY id DESC;";
		} elseif (isset($_POST['date']) && isset($_POST['c-name']) && !isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND language='"
				.$_POST['language']. "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (!isset($_POST['date']) && !isset($_POST['c-name']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE language='"
				.$_POST['language']. "' AND type='"
				.$_POST['typ'] . "' ORDER BY id DESC;";
		} elseif (!isset($_POST['date']) && isset($_POST['c-name']) && !isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE language='"
				.$_POST['language']. "' AND  contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (isset($_POST['date']) && !isset($_POST['c-name']) && !isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND language='"
				.$_POST['language']. "' ORDER BY id DESC;";
		} else {
			return "SELECT * FROM orders WHERE language='" .$_POST['language'] . "' ORDER BY id DESC;";
		}
	} elseif ($col == "name") {
		if (!isset($_POST['date']) && isset($_POST['language']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE language='"
				.$_POST['language']. "' AND type='"
				.$_POST['typ'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (isset($_POST['date']) && !isset($_POST['language']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND type='"
				.$_POST['typ'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (isset($_POST['date']) && isset($_POST['language']) && !isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND language='"
				.$_POST['language']. "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (!isset($_POST['date']) && !isset($_POST['language']) && isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE type='"
				.$_POST['typ'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (!isset($_POST['date']) && isset($_POST['language']) && !isset($_POST['typ'])) {
			return "SELECT * FROM orders WHERE language='"
				.$_POST['language']. "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (isset($_POST['date']) && !isset($_POST['language']) && !isset($_POST['typ'])) {
			 return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} else {
			return "SELECT * FROM orders WHERE contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		}
	} elseif ($col == "type") {
		if (!isset($_POST['date']) && isset($_POST['language']) && isset($_POST['c-name'])) {
			return "SELECT * FROM orders WHERE language='"
				.$_POST['language']. "' AND type='"
				.$_POST['typ'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (isset($_POST['date']) && !isset($_POST['language']) && isset($_POST['c-name'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND type='"
				.$_POST['typ'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (isset($_POST['date']) && isset($_POST['language']) && !isset($_POST['c-name'])) {
			return "SELECT * FROM orders WHERE date='"
				.$_POST['date'] . "' AND language='"
				.$_POST['language']. "' AND type='"
				.$_POST['typ'] . "' ORDER BY id DESC;";
		} elseif (!isset($_POST['date']) && !isset($_POST['language']) && isset($_POST['c-name'])) {
			return "SELECT * FROM orders WHERE type='"
				.$_POST['typ'] . "' AND contactPerson_id IN(SELECT id FROM contactPerson WHERE name='"
				.$_POST['c-name'] . "') ORDER BY id DESC;";
		} elseif (!isset($_POST['date']) && isset($_POST['language']) && !isset($_POST['c-name'])) {
			return "SELECT * FROM orders WHERE language='"
				.$_POST['language']. "' AND type='"
				.$_POST['typ'] . "' ORDER BY id DESC;";
		} elseif (isset($_POST['date']) && !isset($_POST['language']) && !isset($_POST['c-name'])) {
			return "SELECT * FROM orders WHERE date_submit='"
				.$_POST['date'] . "' AND type='"
				.$_POST['typ'] . "' ORDER BY id DESC;";
		} else {
			return "SELECT * FROM orders WHERE type='" .$_POST['typ'] . "' ORDER BY id DESC;";
		}
	} elseif ($col == "number") {
        return "SELECT * FROM orders WHERE order_number='" .$_POST['number'] . "';";
	}
	
}

function getTable() {
	$connection = mysqli_connect(HOST, USER, PASS) or die("Could't connect to MySQL:" . mysqli_error());
	$dbase = mysqli_select_db($connection, DATABASE) or die(mysqli_error($connection));
	$result = mysqli_query($connection, $GLOBALS['query']);
	if (!$result)
		die("Database access failed: " . mysqli_error($connection));
	
	//fetching the result
	if (mysqli_num_rows($result) > 0) {
		echo <<<AOT
<table class='order' title="Order">
			<thead>
				<tr>
					<th>Uppdrag <br /> nummer</th>
					<th>Date Added</th>
					<th>Contact Person</th>
					<th>CP Email</th>
					<th>CP Telephone</th>
					<th>CP Address</th>
					<th>CP Post Number</th>
					<th>CP City</th>
					<th>Type</th>
					<th>Language</th>
					<th>Date</th>
					<th>Start Time</th>
					<th>End Time</th>
					<th>State</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
AOT;
		while ($row = mysqli_fetch_array($result)) {
			$order_arr = array();
			$id = $row["order_number"];
			echo "<tr>";
			echo "<td>$id</td>";
			$contact_id = $row['contactPerson_id'];
			$organization_id = $row['organization_id'];
			$interpreter_id = $row['interpreter_id'];
			$type = $row['type'];
			$language = $row['language'];
			$date = $row['date'];
			$start_time = $row['start_time'];
			$end_time = $row['end_time'];
			$comment = $row['comment'];
			$date_added = $row['date_submit'];
			$state = $row['state'];
			$order_num = $row['id'];
			$order_arr['order_num'] = $order_num;
			$order_arr['order_id'] = $id;
			$order_arr['contact_id'] = $contact_id;
			$order_arr['organization_id'] = $organization_id;
			$order_arr['interpreter_id'] = $interpreter_id;
			$order_arr['type'] = $type;
			$order_arr['language'] = $language;
			$order_arr['date'] = $date;
			$order_arr['start_time'] = $start_time;
			$order_arr['end_time'] = $end_time;
			$order_arr['comment'] = $comment;
			$order_arr['date_added'] = $date_added;
			$order_arr['state'] = $state;
			echo "<td>$date_added</td>";
			$query = "SELECT* FROM contactPerson WHERE id = '" . $contact_id . "';";
			$result_contact = mysqli_query($connection, $query);
			if (!$result_contact)
				die("Database access failed: " . mysqli_error($connection));
			echo " ";
			if (mysqli_num_rows($result_contact) > 0) {
				while ($row_contact = mysqli_fetch_array($result_contact)) {
					$name = $row_contact['name'];
					$email = $row_contact['email'];
					$telephone = $row_contact['telephone'];
					$address = $row_contact['address'];
					$post_number = $row_contact['post_number'];
					$city = $row_contact['city'];

					echo "<td>$name</td>";
					echo "<td>$email</td>";
					echo "<td>$telephone</td>";
					echo "<td>$address</td>";
					echo "<td>$post_number</td>";
					echo "<td>$city</td>";

					$order_arr['contact_name'] = $name;
					$order_arr['contact_email'] = $email;
					$order_arr['contact_telephone'] = $telephone;
					$order_arr['contact_address'] = $address;
					$order_arr['contact_post_number'] = $post_number;
					$order_arr['contact_city'] = $city;
				}
			} else {
				echo "Internal Error!";
			}
			echo "<td>$type</td>";
			echo "<td>$language</td>";
			echo "<td>$date</td>";
			echo "<td>$start_time</td>";
			echo "<td>$end_time</td>";
			$query = "SELECT* FROM organization WHERE id = " . $organization_id . ";";
			$result_contact = mysqli_query($connection, $query);
			if (!$result_contact)
				die("Database access failed: " . mysqli_error($connection));
			echo " ";
			if (mysqli_num_rows($result_contact) > 0) {
				while ($row_contact = mysqli_fetch_array($result_contact)) {
					$boss = $row_contact['boss'];
					$name = $row_contact['name'];
					$address = $row_contact['address'];
					$post_number = $row_contact['post_number'];
					$city = $row_contact['city'];

					//Displais the org/company details
					// echo "<td>$boss</td>";
					// echo "<td>$name</td>";
					// echo "<td>$address</td>";
					// echo "<td>$post_number</td>";
					// echo "<td>$city</td>";

					$order_arr['org_owner'] = $boss;
					$order_arr['org_name'] = $name;
					$order_arr['org_address'] = $address;
					$order_arr['org_post_number'] = $post_number;
					$order_arr['org_city'] = $city;
				}
			} 
			if ($state == "X") {
				echo "<td><img src='../image/uncomplete.png' alt='Not assigned' height='50' width='50'></td>";
			} else {
				echo "<td><img src='../image/complete.png' alt='Assigned' height='50' width='50'></td>";
			}
			echo "<td>";
			echo "<form action='redir.php' method='post' id='$id'>";

			echo "<input type='hidden' name='id' value=" . base64_encode(serialize($order_arr)). " />";

			echo "<input id='btn' type='submit' name='manage$id' value='Förvalta $id' onclick='document.form.id=$id' />";
			echo "</form>";
			echo "</td>";
			echo "</tr>";
		}
		echo <<<AOT
			</tbody>
		</table>
AOT;
		mysqli_close($connection);
	} else {
		echo "<p id='error'>No order with this information has been found!</p><br /><br />";
	}
}
echo "</body></html>"
?>