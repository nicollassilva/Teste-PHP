<?php

namespace Api;

    require_once "app/Vehicles.class.php";

    header("Content-type: application/json; charset=UTF-8");

use \Api\App\Vehicles;

class API extends Vehicles {

    function __construct() {

        parent::__construct();

    }

    public function run(String $http, $id) {

        switch ($http) {
            case 'GET':
                $info = !$id ? self::selectAll('veiculos', '*', null, 'id DESC') : self::selectAll('veiculos', '*', "id = '$id'", null, '1');
                    if($info) { echo json_encode($info); } else { http_response_code(204); }
                break;
            case 'POST':
                if(!isset($_POST['search'])) {
                    if(isset($_POST['vehicle']) && isset($_POST['brand'])) {
                        self::insert('veiculos', 'veiculo, marca, ano, descricao, created, updated', [
                            strip_tags($_POST['vehicle']),
                            strip_tags($_POST['brand']),
                            $_POST['year'],
                            $_POST['description'],
                            date("Y-m-d H:i:s", time()),
                            date("Y-m-d H:i:s", time())
                        ]);
                        self::returnMessage("Carro inserido com sucesso", true);
                        http_response_code(201);
                    }
                } else {
                    $sql = $this->connection->query("SELECT * FROM veiculos WHERE veiculo LIKE '%{$_POST['search']}%' OR marca LIKE '%{$_POST['search']}%' OR ano LIKE '%{$_POST['search']}%'")->fetchAll(\PDO::FETCH_ASSOC);
                    echo json_encode($sql);
                }
                break;
            case 'PUT':
                parse_str(file_get_contents('php://input'), $_PUT);
                if(isset($_PUT['vehicle']) && isset($_PUT['brand'])) {
                    if(self::selectBool('veiculos', 'id', "id = {$_GET['id']}")) {
                        $sold = isset($_PUT['sold']) ? 'true' : 'false';
                        self::update('veiculos', 'veiculo, marca, ano, descricao, vendido, updated', [
                            $_PUT['vehicle'],
                            $_PUT['brand'],
                            $_PUT['year'] ?? '',
                            $_PUT['description'] ?? '',
                            $sold,
                            date("Y-m-d H:i:s", time())
                        ], "id = {$_GET['id']}");
                        self::returnMessage("Veículo editado com sucesso.", false);
                    } else {
                        http_response_code(204);
                    }
                } else {
                    self::returnMessage("Digite os campos obrigatórios: Veículo e Marca", true);
                    http_response_code(400);
                }
                break;
            case 'DELETE':
                    if(self::selectBool('veiculos', 'id', "id = {$_GET['id']}")) {
                        self::delete('veiculos', "id = {$_GET['id']}");
                        self::returnMessage("Veículo deletado com sucesso.", false);
                    } else {
                        http_response_code(204);
                    }
                break;
            default:
                self::returnMessage("Nossa API não aceita esse método http!", true);
                http_response_code(405);
                break;
        }

    }

}

$api = new API();
$api->run(
    $_SERVER['REQUEST_METHOD'],
    $_GET['id'] ?? null
);