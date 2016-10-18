<?php

class Database {
	
	private $host = "mysql.hostinger.ph";
	private $username = "u295054930_lbc";
	private $password = "2fZSZvEyWR9nZmnoSr";
	private $dbname = "u295054930_lbc";
	
	public function connect() {
		return new mysqli(
			$this->host,
			$this->username,
			$this->password,
			$this->dbname);
	}
}

?>