<?php
/**
 * This class will handle the Database connection.
 */
class DB_Connector {
	//constructor
	function __construct()
	{
		$this->connect();
	}
	/**
	 * This method will try to connect to an existing database server.
	 * @return	-> The successful Database connection
	 */
	function connect()
	{
		require 'db-config.php';
		$connection = mysqli_connect(HOST,USER,PASS) or die("Could't connect to MySQL:".mysqli_error());
		$dbase = mysqli_select_db($connection, DATABASE) or die(mysqli_error($connection));
		return $connection;
	}
}
?> 