<?php

namespace src\models;

require_once $_SERVER['DOCUMENT_ROOT'] .'/src/models/BaseModel.php';

use src\models\BaseModel as BaseModel;

class Category extends BaseModel
{

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function getAll()
    {
        try {
            $statement = $this->db->query(SELECT_ALL_CATEGORIES);
            $this->setResult($statement->fetchAll(\PDO::FETCH_ASSOC));
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function getById(int $id)
    {
        try {
            $statement = $this->db->prepare(SELECT_CATEGORIES_BY_ID);
            $statement->execute(array('id' => $id));
            $res = $statement->fetch(\PDO::FETCH_ASSOC);
            if ($res) {
                $this->setResult((object)$res);
            }
        } catch (\PDOException $e) {
            $this->setError($e);
        }
        return array($this->getResult(), $this->getError());
    }

    public function insert(Array $category)
    {
        try {
            $statement = $this->db->prepare(INSERT_CATEGORY);
            $statement->execute(array(
                'name' => $category['name'],
                'parent_id' => (int) $category['parent_id']
            ));
            $category['id'] = $this->db->lastInsertID();
            $this->setResult($category);
        } catch (\PDOException $e) {
            $this->setError($e);
        }
        return array($this->getResult(), $this->getError());

    }

    public function update($id, Array $input)
    {
        try {
            $statement = $this->db->prepare(UPDATE_CATEGORY);
            $statement->execute(array(
                'id' => (int) $id,
                'name' => $input['name'],
                'parent_id' => (int) $input['parent_id']
            ));
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

            // remove relating event_categories
            $statement = $this->db->prepare(DELETE_CAT_EVENT_BY_CATEGORY_ID);
            $statement->execute(array('category_id' => $id));

            // update all child categories to have no parent
            $statement = $this->db->prepare(UPDATE_CATEGORIES_WITH_NO_PARENT);
            $statement->execute(array('parent_id' => $id));

            // finally, remove category
            $statement = $this->db->prepare(DELETE_CATEGORY);
            $statement->execute(array('id' => $id));

            $this->db->commit();
            $this->setResult($statement->rowCount());
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }
}
