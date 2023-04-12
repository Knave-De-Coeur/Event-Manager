<?php

namespace src\controllers;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/models/event.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/controllers/BaseController.php';

use src\controllers\BaseController as BaseController;
use src\models\Event as Event;
use src\models\Response;

class EventController extends BaseController
{
    private Event|null $event;

    public function __construct($db, $requestMethod, $id, $city, $event)
    {
        parent::__construct($db, $requestMethod, $id);

        $this->event = new Event($db, $city, $event);
    }

    public function getAll()
    {
        list($res, $err) = $this->event->getAll();

        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong getting the events.",
                body: new \stdClass,
                errorMsg: $err->getMessage()
            );
        } else {
            $response = new Response(
                code: 200,
                msg: "Successfully got events!",
                body: $res,
                errorMsg: new \stdClass,
            );
        }

        return $response;
    }

    public function getById()
    {
        list($res, $err) = $this->event->getById($this->id);

        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong getting the Event",
                body: new \stdClass,
                errorMsg: $err->getMessage()
            );
        } else if (!$res) {
            return $this->notFoundResponse();
        } else {
            $response = new Response(
                code: 200,
                msg: "Successfully Grabbed the Event!",
                body: $res,
                errorMsg: null,
            );
        }
        return $response;
    }

    public function insert()
    {
        $event = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateEvent($event)) {
            return $this->badRequestResponse();
        }
        list($res, $err) = $this->event->insert($event);

        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong inserting the Event",
                body: new \stdClass,
                errorMsg: $err->getMessage()
            );
        } else {
            $response = new Response(
                code: 201,
                msg: "Event Successfully Inserted!",
                body: $res,
                errorMsg: null,
            );
        }
        return $response;
    }

    public function update()
    {
        $event = $this->event->getById($this->id);
        if (! $event) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateEvent($input)) {
            return $this->badRequestResponse();
        }
        list($res, $err) = $this->event->update($this->id, $input);
        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong updating the event",
                body: new \stdClass,
                errorMsg: $err->getMessage()
            );
        } else if (!$res) {
            $response = new Response(
                code: 404,
                msg: "No Event was updated!",
                body: new \stdClass,
                errorMsg: null,
            );
        } else {
            $response = new Response(
                code: 200,
                msg: "Event Successfully Updated!",
                body: new \stdClass,
                errorMsg: null,
            );
        }
        return $response;
    }

    public function delete()
    {
        $event = $this->event->getById($this->id);
        if (!$event) {
            return $this->notFoundResponse();
        }
        list($res, $err) = $this->event->delete($this->id);
        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong deleting the Event",
                body: new \stdClass,
                errorMsg: $err->getMessage(),
            );
        } else if (!$res) {
            $response = new Response(
                code: 404,
                msg: "Something went wrong",
                body: new \stdClass,
                errorMsg: "No Event was deleted.",
            );
        } else {
            $response = new Response(
                code: 200,
                msg: "Event Successfully Deleted!",
                body: new \stdClass,
                errorMsg: null,
            );
        }
        return $response;
    }

    private function validateEvent($input)
    {
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['organizer'])) {
            return false;
        }
        if (! isset($input['city_id'])) {
            return false;
        }
        if (! isset($input['time_start'])) {
            return false;
        }
        if (! isset($input['time_end'])) {
            return false;
        }
        return true;
    }
}