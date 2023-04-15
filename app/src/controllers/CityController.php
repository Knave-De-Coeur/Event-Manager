<?php
namespace src\controllers;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/models/city.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/controllers/BaseController.php';

use src\models\City as City;
use src\models\Response;
use src\controllers\BaseController as BaseController;

class CityController extends BaseController
{
    private $city;

    const CITY_LIST_KEY = "city_l";

    public function __construct($db, $cache, $requestMethod, $id, $city)
    {
        parent::__construct($db, $cache, $requestMethod, $id);

        if ($city == null) {
            $this->city = new City($db);
        } else {
            $this->city = $city;
        }
    }

    public function getAll()
    {
        $res = $this->cache->get($this::CITY_LIST_KEY);
        if (!empty($res)) {
            return new Response(
                200,
                "Successfully got cities!",
                $res,
                null
            );
        }

        list($res, $err) = $this->city->getAll();

        if ($err != null) {
            $response = new Response(
                $err->getCode(),
                "Something went wrong getting the cities.",
                new \stdClass,
                $err->getMessage()
            );
        } else {
            $this->cache->set($this::CITY_LIST_KEY, $res);
            $response = new Response(
                200,
                "Successfully got cities!",
                $res,
                null
            );
        }

        return $response;
    }

    public function getById()
    {
        list($res, $err) = $this->city->getById($this->id);

        if ($err != null) {
            $response = new Response(
                $err->getCode(),
                "Something went wrong getting the City",
                new \stdClass,
                $err->getMessage()
            );
        } else if (!$res) {
            return $this->notFoundResponse();
        } else {
            $response = new Response(
                200,
                "Successfully Grabbed City!",
                $res,
                null
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
                $err->getCode(),
                "Something went wrong inserting the City",
                new \stdClass,
                $err->getMessage()
            );
        } else {
            $this->cache->del($this::CITY_LIST_KEY);
            $response = new Response(
                201,
                "Category Successfully Inserted!",
                $res,
                null
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
                $err->getCode(),
                "Something went wrong updating the city",
                new \stdClass,
                $err->getMessage()
            );
        } else if (!$res) {
            $response = new Response(
                404,
                "No City was updated!",
                new \stdClass,
                null
            );
        } else {
            $this->cache->del($this::CITY_LIST_KEY);
            $response = new Response(
                200,
                "City Successfully Updated!",
                new \stdClass,
                null
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
                $err->getCode(),
                "Something went wrong deleting the city",
                new \stdClass,
                $err->getMessage()
            );
        } else if (!$res) {
            $response = new Response(
                404,
                "Something went wrong",
                new \stdClass,
                "No City was deleted."
            );
        } else {
            $this->cache->del($this::CITY_LIST_KEY);
            $response = new Response(
                200,
                "City Successfully Deleted!",
                new \stdClass,
                null
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