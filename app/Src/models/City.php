<?php

namespace Src\Models;

require_once $_SERVER['DOCUMENT_ROOT'] .'/Src/Utils/sql.php';

use PDO;
use PDOException;
use Src\Models\BaseModel as BaseModel;

class City extends BaseModel
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function getAll()
    {
        try {
            $statement = $this->db->query(SELECT_CITIES);
            $this->setResult($statement->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function getById($id)
    {
        try {
            $statement = $this->db->prepare(SELECT_CITY_BY_ID);
            $statement->execute(array('id' => $id));
            $res = $statement->fetch(PDO::FETCH_ASSOC);
            if ($res) {
                $this->setResult((object)$res);
            }
        } catch (PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function insert(Array $city)
    {
        try {
            $statement = $this->db->prepare(INSERT_CITY);
            $statement->execute(array(
                'name' => $city['name'],
                'population'  => $city['population'],
                'size' => $city['size'],
                'capital' => (int) $city['capital'],
            ));
            $city['id'] = $this->db->lastInsertID();
            $this->setResult($city);;
        } catch (PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }

    public function update($id, Array $input)
    {
        try {
            $statement = $this->db->prepare(UPDATE_CITY);
            $statement->execute(array(
                'id' => (int) $id,
                'name' => $input['name'],
                'population' => $input['population'],
                'size'  => $input['size'],
                'capital' => (int) $input['capital'],
            ));
            $this->setResult($statement->rowCount());
        } catch (PDOException $e) {
            $this->setError($e);
        }
        return array($this->getResult(), $this->getError());
    }

    public function delete($id)
    {
        try {
            $statement = $this->db->prepare(DELETE_CITY);
            $statement->execute(array('id' => $id));
            $this->setResult($statement->rowCount());
        } catch (PDOException $e) {
            $this->setError($e);
        }

        return array($this->getResult(), $this->getError());
    }
}