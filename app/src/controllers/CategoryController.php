<?php

namespace src\controllers;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/models/category.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/controllers/BaseController.php';

use src\controllers\BaseController as BaseController;
use src\models\Response as Response;
use src\models\Category as Category;

class CategoryController extends BaseController
{
    private $category;
    const CAT_LIST_KEY = "category_l";

    public function __construct($db, $cache, $requestMethod, $id, $category)
    {
        parent::__construct($db, $cache, $requestMethod, $id);

        if ($category == null) {
            $this->category = new Category($db);
        } else {
            $this->category = $category;
        }
    }


    public function getAll()
    {
        $res = $this->cache->get($this::CAT_LIST_KEY);
        if (!empty($res)) {
            return new Response(
                200,
                "Successfully got categories!",
                $res,
                null
            );
        }

        list($res, $err) = $this->category->getAll();
        if ($err != null) {
            $response = new Response(
                $err->getCode(),
                "Something went wrong getting the categories",
                new \stdClass,
                $err->getMsg()
            );

        } else {
            $this->cache->set($this::CAT_LIST_KEY, $res);
            $response = new Response(
                200,
                "Successfully got categories!",
                $res,
                null
            );
        }
        return $response;
    }

    public function getById()
    {
        list($res, $err) = $this->category->getById($this->id);
        if ($res != null) {
            $response = new Response(
                200,
                "Successfully Grabbed Category!",
                $res,
                null
            );
        } else {
            if ($err != null && $err->getCode() == 404 || $err == null) {
                return $this->notFoundResponse();
            }
            $response = new Response(
                $err->getCode(),
                "Something went wrong getting the category",
                new \stdClass,
                $err->getMsg()
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
            $this->cache->del($this::CAT_LIST_KEY);
            $response = new Response(
                201,
                "Category Successfully Inserted!",
                $res,
                null
            );
        } else {
            $response = new Response(
                $err->getCode(),
                "Something went wrong inserting the category",
                new \stdClass,
                $err->getMsg()
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
            $this->cache->del($this::CAT_LIST_KEY);
            $response = new Response(
                200,
                "Category Successfully Updated!",
                new \stdClass,
                null
            );
        } else {
            $response = new Response(
                $err->getCode(),
                "Something went wrong updating the category",
                new \stdClass,
                $err->getMsg()
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
            $this->cache->del($this::CAT_LIST_KEY);
            $response = new Response(
                200,
                "Category Successfully Deleted!",
                new \stdClass,
                null
            );
        } else {
            if ($err != null) {
                $response = new Response(
                    $err->getCode(),
                    "Something went wrong deleting the category",
                    new \stdClass,
                    $err->getMsg()
                );
            } else {
                $response = new Response(
                    404,
                    "Something went wrong",
                    new \stdClass,
                    "No Category was deleted."
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