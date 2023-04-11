<?php

namespace src\utils\classes;

use src\classes\BaseClass as BaseClass;
use src\classes\City as City;
use src\classes\Category as Category;

class Event extends BaseClass
{
    private City|null $city;
    private Category|null $category;
    public function __construct($db, $city, $category)
    {
        parent::__construct($db);
        $this->city = $city;
        $this->category = $category;
    }

    public function getAll()
    {
        try {
            $statement = $this->db->query(select_all_events_with_cat);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($result as $event) {
                $event['category_ids'] = explode(",", $event['category_ids']);
            }
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getById(int $id)
    {
        try {
            $statement = $this->db->prepare(select_event_with_cat_by_id);
            $statement->execute(array('event_id' => $id));
            $event = $statement->fetch(\PDO::FETCH_ASSOC);
            $event['category_ids'] = explode(",", $event['category_ids']);
            return $event;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(array $input)
    {
        try {
            $this->db->beginTransaction();

            $city = $this->city->getById($input['city_id']);

            if ($city == null) {
                $this->db->Rollback();
                throw new \PDOException("city does not exist");
            }

            $statement = $this->db->prepare(insert_event);

            $statement->execute(array(
                'name' => $input['name'],
                'organizer'  => $input['organizer'],
                'city_id' => $input['city_id'],
                'time_start' => $input['time_start'],
                'time_end' => $input['time_end'],
            ));

            $event_id = $this->db->lastInsertId();

            if (count($input['category_ids']) >= 1) {
                if (!$this->insertBulkEventCategories($input['category_ids'], $event_id)) {
                    $this->db->rollback();
                    throw new \PDOException("category doesn't exist");
                }
            }

            $this->db->Commit();

            return $event_id;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(int $id, array $input)
    {
        try {
            $this->db->beginTransaction();

            $city = $this->city->getById($input['city_id']);

            if ($city == null) {
                $this->db->Rollback();
                throw new \PDOException("city does not exist");
            }

            $statement = $this->db->prepare(update_event);
            $statement->execute(array(
                'id' => $id,
                'name' => $input['name'],
                'organizer'  => $input['organizer'],
                'city_id' => $input['city_id'],
                'time_start' => $input['time_start'],
                'time_end' => $input['time_end'],
            ));


            // remove all pre-existing rows and insert anything coming from the request so long as it is valid
            if (count($input['category_ids']) >= 1) {

                $statement = $this->db->prepare(delete_event_categories);
                $statement->execute(array("event_id" => $id));

                if (!$this->insertBulkEventCategories($input['category_ids'], $id)) {
                    $this->db->rollback();
                    throw new \PDOException("category doesn't exist");
                }
            }

            $this->db->Commit();

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $this->db->beginTransaction();

            $statement = $this->db->prepare(select_event_with_cat_by_id);
            $statement->execute(array('event_id' => $id));

            $event = $statement->fetch(\PDO::FETCH_ASSOC);
            $event['category_ids'] = str_split($event['category_ids']);

            if (count($event['category_ids']) >= 1) {
                $statement = $this->db->prepare(delete_event_categories);
                $statement->execute(array('event_id' => $id));
            }

            $statement = $this->db->prepare(delete_event);
            $statement->execute(array('id' => $id));

            $this->db->commit();

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insertBulkEventCategories(array $cat_ids, int $event_id) : bool {
        $statement = $this->db->prepare(insert_event_category);
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
