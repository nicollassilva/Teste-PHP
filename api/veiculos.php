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
                    self::new($_POST);
                } else {
                    self::search($_POST);
                }
                break;
            case 'PUT':
                parse_str(file_get_contents('php://input'), $_PUT);
                    self::edit($_PUT, $id);
                break;
            case 'DELETE':
                    self::destroy($id);
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