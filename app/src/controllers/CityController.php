<?php
namespace src\controllers;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/models/city.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/controllers/BaseController.php';

use src\models\City as City;
use src\models\Response;
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
        list($res, $err) = $this->city->getAll();

        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong getting the cities.",
                body: new \stdClass,
                errorMsg: $err->getMessage()
            );
        } else {
            $response = new Response(
                code: 200,
                msg: "Successfully got cities!",
                body: $res,
                errorMsg: null,
            );
        }

        return $response;
    }

    public function getById()
    {
        list($res, $err) = $this->city->getById($this->id);

        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong getting the City",
                body: new \stdClass,
                errorMsg: $err->getMessage()
            );
        } else if (!$res) {
            return $this->notFoundResponse();
        } else {
            $response = new Response(
                code: 200,
                msg: "Successfully Grabbed City!",
                body: $res,
                errorMsg: null,
            );
        }
        return $response;
    }

    public function insert()
    {
        $city = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateCity($city)) {
            return $this->badRequestResponse();
        }

        list($res, $err) = $this->city->insert($city);

        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong inserting the City",
                body: new \stdClass,
                errorMsg: $err->getMessage()
            );
        } else {
            $response = new Response(
                code: 201,
                msg: "Category Successfully Inserted!",
                body: $res,
                errorMsg: null,
            );
        }
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
        list($res, $err) = $this->city->update($this->id, $input);
        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong updating the city",
                body: new \stdClass,
                errorMsg: $err->getMsg()
            );
        } else if (!$res) {
            $response = new Response(
                code: 404,
                msg: "No City was updated!",
                body: new \stdClass,
                errorMsg: null,
            );
        } else {
            $response = new Response(
                code: 200,
                msg: "City Successfully Updated!",
                body: new \stdClass,
                errorMsg: null,
            );
        }
        return $response;
    }

    public function delete()
    {
        $city = $this->city->getById($this->id);
        if (!$city) {
            return $this->notFoundResponse();
        }
        list($res, $err) = $this->city->delete($this->id);
        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong deleting the city",
                body: new \stdClass,
                errorMsg: $err->getMessage(),
            );
        } else if (!$res) {
            $response = new Response(
                code: 404,
                msg: "Something went wrong",
                body: new \stdClass,
                errorMsg: "No City was deleted.",
            );
        } else {
            $response = new Response(
                code: 200,
                msg: "City Successfully Deleted!",
                body: new \stdClass,
                errorMsg: null,
            );
        }
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