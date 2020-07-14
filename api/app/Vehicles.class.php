<?php

namespace Api\App;

    require_once "Connect.class.php";

use \Api\App\Connect;

class Vehicles extends Connect {

    function __construct() {

        parent::__construct();

    }

    public function new($data) {

        if(isset($data['vehicle']) && isset($data['brand'])) {
            self::insert('veiculos', 'veiculo, marca, ano, descricao, created, updated', [
                strip_tags(trim($data['vehicle'])),
                strip_tags(trim($data['brand'])),
                $data['year'],
                $data['description'],
                date("Y-m-d H:i:s", time()),
                date("Y-m-d H:i:s", time())
            ]);
            self::returnMessage("Carro inserido com sucesso", true);
            http_response_code(201);
        } else {
            self::returnMessage("Digite os campos obrigatórios: Veículo e Marca", true);
            http_response_code(400);
        }

    }

    public function edit($data, $id) {

        if(isset($data['vehicle']) && isset($data['brand']) && $id !== null) {
            if(self::selectBool('veiculos', 'id', "id = {$_GET['id']}")) {
            $sold = isset($data['sold']) ? 'true' : 'false';
            self::update('veiculos', 'veiculo, marca, ano, descricao, vendido, updated', [
                strip_tags(trim($data['vehicle'])),
                strip_tags(trim($data['brand'])),
                $data['year'] ?? '',
                $data['description'] ?? '',
                $sold,
                date("Y-m-d H:i:s", time())
            ], "id = $id");
            self::returnMessage("Veículo editado com sucesso.", false);
            } else {
                http_response_code(204);
            }
        } else {
            self::returnMessage("Digite os campos obrigatórios.", true);
            http_response_code(400);
        }

    }

    public function search($data) {

        $sql = $this->connection->query("SELECT * FROM veiculos WHERE veiculo LIKE '%{$data['search']}%' OR marca LIKE '%{$data['search']}%' OR ano LIKE '%{$data['search']}%'")->fetchAll(\PDO::FETCH_ASSOC);
        echo json_encode($sql);

    }

    public function destroy($id) {

        if($id !== null) {
            if(self::selectBool('veiculos', 'id', "id = $id")) {
                self::delete('veiculos', "id = $id");
                self::returnMessage("Veículo deletado com sucesso.", false);
            }
        } else {
            http_response_code(204);
        }

    }
    
}