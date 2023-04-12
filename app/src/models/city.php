<?php

namespace src\models;

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/utils/sql.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/models/BaseModel.php';

use src\models\BaseModel as BaseModel;

class City extends BaseModel
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function getAll()
    {
        try {
            $statement = $this->db->query(select_all_cities);
            $this->setResult($statement->fetchAll(\PDO::FETCH_ASSOC));
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function getById($id)
    {
        try {
            $statement = $this->db->prepare(select_city_by_id);
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

    public function insert(Array $city)
    {
        try {
            $statement = $this->db->prepare(insert_city);
            $statement->execute(array(
                'name' => $city['name'],
                'population'  => $city['population'],
                'size' => $city['size'],
                'capital' => $city['capital'],
            ));
            $city['id'] = $this->db->lastInsertID();
            $this->setResult($city);;
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function update($id, Array $input)
    {
        try {
            $statement = $this->db->prepare(update_city);
            $statement->execute(array(
                'id' => (int) $id,
                'name' => $input['name'],
                'population' => $input['population'],
                'size'  => $input['size'],
                'capital' => $input['capital'],
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
            $statement = $this->db->prepare(delete_city);
            $statement->execute(array('id' => $id));
            $this->setResult($statement->rowCount());
        } catch (\PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }
}