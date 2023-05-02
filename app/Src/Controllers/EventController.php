<?php

namespace Src\Controllers;

use Src\Controllers\BaseController as BaseController;
use Src\Models\Event as Event;
use Src\Models\Response;

class EventController extends BaseController
{
    private $event;

    const EVENT_LIST_KEY = "event_l";

    public function __construct($db, $cache, $requestMethod, $id, $city, $category)
    {
        parent::__construct($db, $cache, $requestMethod, $id);
        $this->event = new Event($db, $city, $category);
    }

    public function getAll()
    {
        $res = $this->cache->get($this::EVENT_LIST_KEY);
        if (!empty($res)) {
            return new Response(
                code: 200,
                msg: "Successfully got events!",
                body: $res,
                errorMsg: null,
            );
        }

        list($res, $err) = $this->event->getAll();

        if ($err != null) {
            $response = new Response(
                code: $err->getCode(),
                msg: "Something went wrong getting the events.",
                body: new \stdClass,
                errorMsg: $err->getMessage()
            );
        } else {
            $this->cache->set($this::EVENT_LIST_KEY, $res);
            $response = new Response(
                code: 200,
                msg: "Successfully got events!",
                body: $res,
                errorMsg: null,
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
        } elseif (!$res) {
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
            $this->cache->del($this::EVENT_LIST_KEY);
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
            $this->cache->del($this::EVENT_LIST_KEY);
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
        } elseif (!$res) {
            $response = new Response(
                code: 404,
                msg: "Something went wrong",
                body: new \stdClass,
                errorMsg: "No Event was deleted.",
            );
        } else {
            $this->cache->del($this::EVENT_LIST_KEY);
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
        if (!isset($input['name'])) {
            return false;
        }
        if (!isset($input['organizer'])) {
            return false;
        }
        if (!isset($input['description'])) {
            return false;
        }
        if (!isset($input['city_id']) || $input['city_id'] < 1) {
            return false;
        }
        if (!isset($input['time_start'])) {
            return false;
        }
        if (!isset($input['time_end'])) {
            return false;
        }
        return true;
    }
}