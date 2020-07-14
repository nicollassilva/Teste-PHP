<?php

namespace Api\App;

class Connect {
    const HOSTNAME = 'localhost';
    const USERNAME = 'root';
    const PASSWORD = '';
    const DBNAME = 'api';
    protected $connection;

    function __construct() {

        setlocale(LC_ALL, 'portuguese', 'pt_BR', 'pt_BR.utf8');
        date_default_timezone_set("America/Sao_Paulo");

        try {

            $pdo = new \PDO("mysql:host=".self::HOSTNAME.";charset=utf8;dbname=".self::DBNAME, self::USERNAME, self::PASSWORD);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection = $pdo;

        } catch(PDOException $error) {

            echo utf8_encode('Erro ao conectar com o banco de dados: '. $error->getMessage());

        }

    }

    public function insert($table, String $params, Array $values) {

        $parameters = "(".$params.")";

        $params = explode(', ', $params);

        $data = [];

        for($i = 0; $i < count($params); $i++) {

            $data[$i] = ":".$params[$i][0].$params[$i][1].$params[$i][2].$i;
        
        }

        $valueBind = "(".implode(', ', $data).")";

        $sql = $this->connection->prepare("INSERT INTO $table $parameters VALUES $valueBind");

        for($i = 0; $i < count($params); $i++) {

            $sql->bindParam($data[$i], $values[$i]);

        }

        if($sql->execute()) {

            return true;

        } else {

            echo "Erro: ". $sql->errorInfo();

        }

    }

    public function selectBool($table, String $params, $where) : bool {

        $where = $where != '' ? $where = "WHERE ".$where : $where = '';

        $sql = $this->connection->query("SELECT $params FROM $table $where");

        $sql->execute();

        return $sql->rowCount() > 0 ? true : false;
    }

    public function selectAll($table, $columns, $where, $order = '', $limit = '') {

        $limit = $limit != '' ? $limit = "LIMIT ".$limit : $limit = '';
        $where = $where != '' ? $where = "WHERE ".$where : $where = '';
        $order = $order != '' ? $order = "ORDER BY ".$order : $order = '';

        $sql = $this->connection->query("SELECT $columns FROM $table $where $order $limit");

        if($sql->rowCount() > 1) {

            $row = $sql->fetchAll(\PDO::FETCH_ASSOC);

        } else {

            $row = $sql->fetch(\PDO::FETCH_ASSOC);

        }

        return $row;

    }

    public function delete($table, $where) {

        $sql = $this->connection->prepare("DELETE FROM $table WHERE $where");
        if($sql->execute()) {

            return true;

        } else {

            echo "Erro: ". $sql->errorInfo();

        }

    }

    public function update($table, String $params, Array $values, $where) {

        $where = $where != '' ? $where = "WHERE ".$where : $where = '';

        $params = explode(', ', $params);

        $data = [];

        for($i = 0; $i < count($params); $i++) {

            $data[$i] = ":".$params[$i][0].$params[$i][1].$params[$i][2].", ";
        
        }

        $result = '';

        $final = array_map(null, $params, $data);

        foreach($final as $key => $vals) {

            foreach($vals as $chave => $val) {

                $result .= str_replace(':', ' = :', $val);

            }

        }

        $result = rtrim($result, ', ');

        $sql = $this->connection->prepare("UPDATE $table SET $result $where");
        
        for($i = 0; $i < count($params); $i++) {

            $data[$i] = ":".$params[$i][0].$params[$i][1].$params[$i][2];
        
        }

        for($i = 0; $i < count($data); $i++) {

            $sql->bindParam($data[$i], $values[$i]);

        }

        if($sql->execute()) {

            return true;

        } else {

            echo "Erro:". $sql->errorInfo();

        }

    }

    public function returnMessage($message, Bool $error) {

        echo json_encode(['msg' => $message, 'error' => $error]);

    }
    


}