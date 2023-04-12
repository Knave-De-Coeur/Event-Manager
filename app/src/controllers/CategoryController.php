<?php

namespace src\controllers;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/models/category.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/controllers/BaseController.php';

use src\controllers\BaseController as BaseController;
use src\models\Response as Response;
use src\models\Category as Category;

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
        list($res, $err) = $this->category->getAll();
        if ($res != null) {
            $response = new Response(
                code: 200,
                msg: "Successfully got categories!",
                body: $res,
                errorMsg: null,
            );
        } else {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong getting the categories",
                body: new \stdClass,
                errorMsg: $err->getMsg()
            );
        }
        return $response;
    }

    public function getById()
    {
        list($res, $err) = $this->category->getById($this->id);
        if ($res != null) {
            $response = new Response(
                code: 200,
                msg: "Successfully Grabbed Category!",
                body: $res,
                errorMsg: null,
            );
        } else {
            if ($err != null && $err->getCode() == 404 || $err == null) {
                return $this->notFoundResponse();
            }
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong getting the category",
                body: new \stdClass,
                errorMsg: $err->getMsg()
            );
        }
        return $response;
    }

    public function insert()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateCategory($input)) {
            return $this->badRequestResponse();
        }
        list($res, $err) = $this->category->insert($input);
        if ($res != null) {
            $response = new Response(
                code: 201,
                msg: "Category Successfully Inserted!",
                body: $res,
                errorMsg: null,
            );
        } else {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong inserting the category",
                body: new \stdClass,
                errorMsg: $err->getMsg()
            );
        }
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
        list($res, $err) = $this->category->update($this->id, $input);
        if ($res != null) {
            $response = new Response(
                code: 200,
                msg: "Category Successfully Updated!",
                body: new \stdClass,
                errorMsg: null,
            );
        } else {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong updating the category",
                body: new \stdClass,
                errorMsg: $err->getMsg()
            );
        }
        return $response;
    }

    public function delete()
    {
        $result = $this->category->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        list($res, $err) = $this->category->delete($this->id);
        if ($res) {
            $response = new Response(
                code: 200,
                msg: "Category Successfully Deleted!",
                body: new \stdClass,
                errorMsg: null,
            );
        } else {
            if ($err != null) {
                $response = new Response(
                    code: $err->getCode(),
                    msg: "Something went wrong deleting the category",
                    body: new \stdClass,
                    errorMsg: $err->getMsg(),
                );
            } else {
                $response = new Response(
                    code: 404,
                    msg: "Something went wrong",
                    body: new \stdClass,
                    errorMsg: "No Category was deleted.",
                );
            }
        }
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