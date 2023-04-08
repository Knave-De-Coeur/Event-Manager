<?php

namespace src\controllers\BaseController;

abstract class BaseController
{
    protected $db;
    private $requestMethod;

    protected $id;

    public function __construct($db, $requestMethod, $id)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->id) {
                    $response = $this->getById();
                } else {
                    $response = $this->getAll();
                }
                break;
            case 'POST':
            case 'PATCH':
                $response = $this->insert();
                break;
            case 'PUT':
                $response = $this->update();
                break;
            case 'DELETE':
                $response = $this->delete();
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

    abstract public function getAll();
    abstract public function getById();
    abstract public function insert();
    abstract public function update();
    abstract public function delete();

    protected function badRequestResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    protected function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'error' => 'data not found'
        ]);;
        return $response;
    }

}
