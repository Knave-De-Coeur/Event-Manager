<?php

namespace src\controllers;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/classes/event.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/controllers/BaseController.php';

use src\controllers\BaseController as BaseController;
use src\utils\classes\Event as Event;

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
        $result = $this->event->getAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    public function getById()
    {
        $result = $this->event->getById($this->id);
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
        if (! $this->validateEvent($input)) {
            return $this->badRequestResponse();
        }
        $result = $this->event->insert($input);
        $input['id'] = (int) $result;
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($input);
        return $response;
    }

    public function update()
    {
        $result = $this->event->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateEvent($input)) {
            return $this->badRequestResponse();
        }
        $this->event->update($this->id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    public function delete()
    {
        $result = $this->event->getById($this->id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->event->delete($this->id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
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