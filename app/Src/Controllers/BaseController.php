<?php

namespace Src\Controllers;

use Src\Models\Response as Response;
use stdClass;

abstract class BaseController
{
    protected $db;

    public $cache;
    private $requestMethod;

    protected $id;

    public function __construct($db, $cache, $requestMethod, $id) {
        $this->db = $db;
        $this->cache = $cache;
        $this->requestMethod = $requestMethod;
        $this->id = $id;
    }

    public function processRequest() {
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
        $this->processResponse($response);
    }

    public function processResponse(Response $response) {
        switch ($response->getCode()) {
            case 200:
                $headerCode = 'HTTP/1.1 200 OK';
                break;
            case 201:
                $headerCode = 'HTTP/1.1 201 Created';
                break;
            case 404:
                $headerCode ='HTTP/1.1 404 Not Found';
                break;
            case 400:
                $headerCode = 'HTTP/1.1 400 Bad Request';
                break;
            default:
                $headerCode ='HTTP/1.1 500 Internal Server Error';
        }
        header($headerCode);
        echo json_encode($response->jsonSerialize());
    }

    abstract public function getAll();
    abstract public function getById();
    abstract public function insert();
    abstract public function update();
    abstract public function delete();

    protected function badRequestResponse()
    {
        return new Response(
            400,
            "something went wrong.",
            new stdClass(),
            "Invalid Input"
        );
    }

    protected function notFoundResponse()
    {
        return new Response(
            404,
            "something went wrong.",
            new stdClass(),
            "Row doesn't exist."
        );
    }

}
