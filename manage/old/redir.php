<?php
ob_start();
session_start();
require 'dbconnector.php';
require 'db-config.php';
if (isset($_POST['id'])) {
	$_SESSION['order'] = unserialize(base64_decode($_POST['id']));
	if ($_SESSION['order']['state'] == "O") {
		$connection = mysqli_connect(HOST, USER, PASS) or die("Could't connect to MySQL:" . mysqli_error());
		$dbase = mysqli_select_db($connection, DATABASE) or die(mysqli_error($connection));
		$result = mysqli_query($connection, "SELECT * FROM interpreter WHERE id=" . $_SESSION['order']['interpreter_id'] . ";");
		if (!$result)
			die("Database access failed: " . mysqli_error($connection));

		//fetching the result
		if (mysqli_num_rows($result) > 0) {
			$row_contact = mysqli_fetch_array($result);
			$_SESSION['order']['tolk-name'] = $row_contact['name'];
			$_SESSION['order']['tolk-email'] = $row_contact['email'];
			$_SESSION['order']['tolk-telephone'] = $row_contact['telephone'];
			$_SESSION['order']['tolk-city'] = $row_contact['city'];
		} else {
			die("Error.");
		}
	}
}
header("Location: manage.php");

exit ;
?>