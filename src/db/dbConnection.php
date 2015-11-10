<?php
/**
 * User: Samuil
 * Date: 25-01-2015
 * Time: 2:22 AM
 */
class dbConnection
{

    private $connection;
    private $host;
    private $db;
    private $user;
    private $pass;

    /**
     * This is the Constructor for this class.
     * It accepts four parameters used in the
     * Database connection.
     * @param $host - The host address.
     * @param $db - The name of the DB
     * @param $user - User name
     * @param $pass - Password
     */
    function __construct($host, $db, $user, $pass) {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * This method will establish and return the successful Database connection.
     * @return null|PDO|string
     */
    function get_connection()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->host;port=3306;dbname=$this->db;charset=utf8", $this->user, $this->pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("set names utf8");
            if ($this->connection != NULL) return $this->connection;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    /**
     * This method is used to close the Database connection.
     */
    function  disconnect () {
        if ($this->connection != NULL) $this->connection = NULL;
    }

    /**
     * This method is used to establish a persisted connection.
     * @return null|PDO|string
     */
    function  get_persisted_connection() {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8", $this->user, $this->pass,array(
                PDO::ATTR_PERSISTENT => true,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ));
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($this->connection != NULL) return $this->connection;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}