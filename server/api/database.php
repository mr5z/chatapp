<?php

class Database {
    private $host = "mysql.hostinger.ph";
    private $username = "";
    private $password = "";
    private $dbname = "";

    public function connect() {
        return new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->dbname);
    }
}

?>
