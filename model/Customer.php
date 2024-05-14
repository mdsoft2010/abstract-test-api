<?php

namespace Model;

use Config\{DB, Cache};

class Customer extends DB
{

    protected static $TBL = null;

    protected static function getTableName()
    {
        if (self::$TBL === null) {
            self::$TBL = getenv('TBL_CUSTOMER');
        }
        return self::$TBL;
    }

    public static function get($id = null)
    {
        if ($id) {
            $result = Cache::get('cid_' . $id);
            if ($result) {
                return $result;
            }
            $db = new DB(self::getTableName());
            $result = $db->getById($id);
            if ($result) {
                Cache::set('cid_' . $id, json_encode($result));
                return $result;
            } else {
                http_response_code(400);
                return "No records found for id $id";
            }
        }

        $db = new DB(self::getTableName());
        $result = $db->all();
        if ($result) {
            return $result;
        } else {
            http_response_code(400);
            return "Table is empty";
        }
    }

    public static function store($nome, $cognome, $gender)
    {
        $db = new DB(self::getTableName());
        $mysqli = $db->connect();

        $nome = mysqli_real_escape_string($mysqli, $nome);
        $cognome = mysqli_real_escape_string($mysqli, $cognome);
        $gender = mysqli_real_escape_string($mysqli, $gender);

        $sql = "INSERT INTO `" . self::getTableName() . "` (`nome`, `cognome`, `gender`) VALUES ('$nome', '$cognome', '$gender')";
        try {
            $mysqli->query($sql);
            return 'Cliente creato con successo';
        } catch (\Exception $e) {
            http_response_code(400);
            return $e->getMessage();
        }
    }
}
