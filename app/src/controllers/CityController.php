<?php
namespace src\controllers\CityController;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/classes/city.php';

use src\classes\city\City;

class CityController
{
    private $db;
    private $requestMethod;
    private $cityId;

    private $city;

    public function __construct($db, $requestMethod, $cityId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->cityId = $cityId;

        $this->city = new City($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->cityId) {
                    $response = $this->getCity($this->cityId);
                } else {
                    $response = $this->getAllCities();
                };
                break;
            case 'POST':
            case 'PATCH':
                $response = $this->createCity();
                break;
            case 'PUT':
                $response = $this->updateCity($this->cityId);
                break;
            case 'DELETE':
                $response = $this->deleteCity($this->cityId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllCities()
    {
        $result = $this->city->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getCity($id)
    {
        $result = $this->city->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createCity()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateCity($input)) {
            return $this->badRequestResponse();
        }
        $result = $this->city->insert($input);
        $input['Id'] = (int) $result;
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($input);
        return $response;
    }

    private function updateCity($id)
    {
        $result = $this->city->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateCity($input)) {
            return $this->badRequestResponse();
        }
        $this->city->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteCity($id)
    {
        $result = $this->city->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->city->delete($id);
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

    private function badRequestResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}