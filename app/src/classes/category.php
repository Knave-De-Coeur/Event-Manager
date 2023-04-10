<?php

namespace src\classes;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/classes/BaseClass.php';

use src\classes\BaseClass as BaseClass;

class Category extends BaseClass
{

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function getAll()
    {
        try {
            $statement = $this->db->query(select_all_categories);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($result as $event) {
                $event['category_ids'] = str_split($event['category_ids']);
            }

            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getById(int $id)
    {
        try {
            $statement = $this->db->prepare(select_category_by_id);
            $statement->execute(array($id));
            $event = $statement->fetch(\PDO::FETCH_ASSOC);
            $event['category_ids'] = str_split($event['category_ids']);
            return $event;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        try {
            $statement = $this->db->prepare(insert_category);
            $statement->execute(array(
                'name' => $input['name'],
                'parentId' => (int) $input['parent_id']
            ));
            return $this->db->lastInsertID();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        try {
            $statement = $this->db->prepare(update_category);
            $statement->execute(array(
                'id' => (int) $id,
                'name' => $input['name'],
                'parentId' => (int) $input['parent_id']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $statement = $this->db->prepare(delete_category);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
