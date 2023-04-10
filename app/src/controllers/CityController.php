<?php
namespace src\controllers;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/classes/city.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/controllers/BaseController.php';

use src\classes\City as City;
use src\controllers\BaseController as BaseController;

class CityController extends BaseController
{
    private City $city;

    public function __construct($db, $requestMethod, $id, $city)
    {
        parent::__construct($db, $requestMethod, $id);

        if ($city == null) {
            $this->city = new City($db);
        } else {
            $this->city = $city;
        }
    }

    public function getAll()
    {
        $result = $this->city->getAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    public function getById()
    {
        $result = $this->city->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    public function insert()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateCity($input)) {
            return $this->badRequestResponse();
        }
        $result = $this->city->insert($input);
        $input['id'] = (int) $result;
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($input);
        return $response;
    }

    public function update()
    {
        $result = $this->city->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateCity($input)) {
            return $this->badRequestResponse();
        }
        $this->city->update($this->id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    public function delete()
    {
        $result = $this->city->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->city->delete($this->id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateCity($input)
    {
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['population'])) {
            return false;
        }
        if (! isset($input['size'])) {
            return false;
        }
        return true;
    }
}