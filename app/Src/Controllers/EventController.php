<?php

namespace Src\Controllers;

use Src\Controllers\BaseController as BaseController;
use Src\Models\Event as Event;
use Src\Models\Response;
use stdClass;

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
                200,
                "Successfully got events!",
                $res,
                null
            );
        }

        list($res, $err) = $this->event->getAll();

        if ($err != null) {
            $response = new Response(
                $err->getCode(),
                "Something went wrong getting the events.",
                new stdClass(),
                $err->getMessage()
            );
        } else {
            $this->cache->set($this::EVENT_LIST_KEY, $res);
            $response = new Response(
                200,
                "Successfully got events!",
                $res,
                null
            );
        }

        return $response;
    }

    public function getById()
    {
        list($res, $err) = $this->event->getById($this->id);

        if ($err != null) {
            $response = new Response(
                $err->getCode(),
               "Something went wrong getting the Event",
                new stdClass(),
                $err->getMessage()
            );
        } elseif (!$res) {
            return $this->notFoundResponse();
        } else {
            $response = new Response(
                200,
                "Successfully Grabbed the Event!",
                $res,
                null
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
                $err->getCode(),
                "Something went wrong inserting the Event",
                new stdClass(),
                $err->getMessage()
            );
        } else {
            $this->cache->del($this::EVENT_LIST_KEY);
            $response = new Response(
                201,
                "Event Successfully Inserted!",
                $res,
                null
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
            return new Response(
                $err->getCode(),
                "Something went wrong updating the event",
                new stdClass(),
                $err->getMessage()
            );
        }

        if (!is_null($res)) {
            $this->cache->del($this::EVENT_LIST_KEY);
            $response = new Response(
                200,
                "Event Successfully Updated!",
                new stdClass(),
                null
            );
        } else {
            $response = new Response(
                500,
                "Event Update Error",
                new stdClass(),
                "Internal Error"
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
        if (!is_null($err)) {
            return new Response(
                $err->getCode(),
                "Something went wrong deleting the Event",
                new stdClass(),
                $err->getMessage()
            );
        }

        if (!is_null($res)) {
            $this->cache->del($this::EVENT_LIST_KEY);
            $response = new Response(
                200,
                "Event Successfully Deleted!",
                new stdClass(),
                null
            );
        } else {
            $response = new Response(
                500,
                "Something went wrong",
                new stdClass(),
                "Internal Error."
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