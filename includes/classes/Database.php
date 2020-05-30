<?php
class Database 
{
	public $conn;

	// Constructor
	function __construct() {
		$servername = 'localhost';
		$username = 'root';
		$password = '';
		$database = 'habo';

		try {
			$this->conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			
			// Set PDO error mode to exception
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// echo 'Connected succesfully!'; # For debugging purposes
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage(); 
		}
	}
}
?>