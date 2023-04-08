<?php

namespace src\controllers\CategoryController;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/classes/category.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/controllers/BaseController.php';

use src\controllers\BaseController\BaseController;
use src\classes\category\Category;

class CategoryController extends BaseController
{
    private $catgeory;

    public function __construct($db, $requestMethod, $id)
    {
        parent::__construct($db, $requestMethod, $id);

        $this->catgeory = new Category($db);
    }


    public function getAll()
    {
        $result = $this->catgeory->getAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    public function getById()
    {
        $result = $this->catgeory->getById($this->id);
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
        if (! $this->validateCategory($input)) {
            return $this->badRequestResponse();
        }
        $result = $this->catgeory->insert($input);
        $input['id'] = (int) $result;
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($input);
        return $response;

    }

    public function update()
    {
        $result = $this->catgeory->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateCategory($input)) {
            return $this->badRequestResponse();
        }
        $this->catgeory->update($this->id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    public function delete()
    {
        $result = $this->catgeory->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->catgeory->delete($this->id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;

    }

    private function validateCategory($input)
    {
        if (! isset($input['name'])) {
            return false;
        }
        return true;
    }

}