<?php

namespace src\controllers;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/classes/category.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/controllers/BaseController.php';

use src\controllers\BaseController as BaseController;
use src\classes\Category as Category;

class CategoryController extends BaseController
{
    private Category $category;

    public function __construct($db, $requestMethod, $id, $category)
    {
        parent::__construct($db, $requestMethod, $id);

        if ($category == null) {
            $this->category = new Category($db);
        } else {
            $this->category = $category;
        }
    }


    public function getAll()
    {
        $result = $this->category->getAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    public function getById()
    {
        $result = $this->category->getById($this->id);
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
        $result = $this->category->insert($input);
        $input['id'] = (int) $result;
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($input);
        return $response;

    }

    public function update()
    {
        $result = $this->category->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateCategory($input)) {
            return $this->badRequestResponse();
        }
        $this->category->update($this->id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    public function delete()
    {
        $result = $this->category->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->category->delete($this->id);
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