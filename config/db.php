<?php

namespace Config;

class DB
{
    private $host;
    private $username;
    private $password;
    private $db;
    private $port;
    private $connection;
    private $tbl;

    public function __construct($tbl)
    {
        $this->host = getenv('DB_HOST');
        $this->username = getenv('DB_USER');
        $this->password = getenv('PASSWORD');
        $this->db = getenv('DB_NAME');
        $this->port = getenv('DB_PORT');
        $this->tbl = $tbl;
        $this->connect();
    }

    public function connect()
    {
        try {
            $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->db, $this->port);

            if (!$this->connection) {
                throw new \Exception("Connessione fallita: " . mysqli_connect_error());
            }

            return $this->connection;
        } catch (\Exception $e) {
            die("Errore durante la connessione al database: " . $e->getMessage());
        }
    }


    public function all()
    {
        $mysqli = $this->connection;
        $tbl = $this->tbl;
        if ($result = $mysqli->query("SELECT * FROM $tbl")) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $this->close();
            return $data;
        }
    }

    public function getById($id)
    {
        $mysqli = $this->connection;
        $tbl = $this->tbl;
        if ($result = $mysqli->query("SELECT * FROM $tbl WHERE id = $id")) {
            $row = $result->fetch_assoc();
            $this->close();
            return $row;
        }
    }

    public function close()
    {
        mysqli_close($this->connection);
    }
}
