<?php

namespace src\models;

use src\models\BaseModel as BaseModel;
use src\models\City as City;
use src\models\Category as Category;

class Event extends BaseModel
{
    private $city;
    private $category;
    public function __construct($db, $city, $category)
    {
        parent::__construct($db);
        $this->city = $city;
        $this->category = $category;
    }

    public function getAll()
    {
        try {
            $statement = $this->db->query(SELECT_ALL_EVENTS);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($result as $event) {
                if (isset($event['category_ids'])) {
                    $event['category_ids'] = explode(",", $event['category_ids']);
                }
            }
            $this->setResult($result);
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function getById($id)
    {
        try {
            $statement = $this->db->prepare(SELECT_EVENT_BY_ID);
            $statement->execute(array('event_id' => $id));
            $event = $statement->fetch(\PDO::FETCH_ASSOC);
            if ($event) {
                if (isset($event['category_ids'])) {
                    $event['category_ids'] = explode(",", $event['category_ids']);
                }
                $this->setResult((object)$event);
            }
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function insert(array $event)
    {
        try {
            $this->db->beginTransaction();

            $city = $this->city->getById($event['city_id']);

            if ($city == null) {
                $this->db->Rollback();
                throw new \PDOException("city does not exist");
            }

            $statement = $this->db->prepare(INSERT_EVENT);

            $statement->execute(array(
                'name' => $event['name'],
                'organizer'  => $event['organizer'],
                'description'  => $event['description'],
                'city_id' => $event['city_id'],
                'time_start' => $event['time_start'],
                'time_end' => $event['time_end'],
            ));

            $event_id = $this->db->lastInsertId();

            if (count($event['category_ids']) >= 1) {
                if (!$this->insertBulkEventCategories($event['category_ids'], $event_id)) {
                    $this->db->rollback();
                    throw new \PDOException("category doesn't exist");
                }
            }

            $this->db->Commit();

            $event['id'] = $event_id;

            $this->setResult($event);
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function update($id, array $input)
    {
        try {
            $this->db->beginTransaction();

            $city = $this->city->getById($input['city_id']);

            if ($city == null) {
                $this->db->Rollback();
                throw new \PDOException("city does not exist");
            }

            $statement = $this->db->prepare(UPDATE_EVENT);
            $statement->execute(array(
                'id' => $id,
                'name' => $input['name'],
                'organizer'  => $input['organizer'],
                'description'  => $input['description'],
                'city_id' => $input['city_id'],
                'time_start' => $input['time_start'],
                'time_end' => $input['time_end'],
            ));


            // remove all pre-existing rows and insert anything coming from the request so long as it is valid
            if (count($input['category_ids']) >= 1) {

                $statement = $this->db->prepare(DELETE_EVENT_CATEGORIES);
                $statement->execute(array("event_id" => $id));

                if (!$this->insertBulkEventCategories($input['category_ids'], $id)) {
                    $this->db->rollback();
                    throw new \PDOException("category doesn't exist");
                }
            }

            $this->db->Commit();

            $this->setResult($statement->rowCount());
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function delete($id)
    {
        try {
            $this->db->beginTransaction();

            $statement = $this->db->prepare(SELECT_EVENT_BY_ID);
            $statement->execute(array('event_id' => $id));

            $event = $statement->fetch(\PDO::FETCH_ASSOC);
            $event['category_ids'] = str_split($event['category_ids']);

            if (count($event['category_ids']) >= 1) {
                $statement = $this->db->prepare(DELETE_EVENT_CATEGORIES);
                $statement->execute(array('event_id' => $id));
            }

            $statement = $this->db->prepare(DELETE_EVENT);
            $statement->execute(array('id' => $id));

            $this->db->commit();

            $this->setResult($statement->rowCount());
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function insertBulkEventCategories(array $cat_ids, int $event_id) : bool {
        $statement = $this->db->prepare(INSERT_EVENT_CATEGORY);
        foreach ($cat_ids as $cat_id) {
            $cat = $this->category->getById($cat_id);
            if ($cat == null) {
                return false;
            }
            $statement->execute(array(
                'event_id' => $event_id,
                'category_id'  => $cat_id,
            ));
        }

        return true;
    }
}
