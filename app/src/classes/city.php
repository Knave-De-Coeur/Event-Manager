<?php

namespace src\classes;

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/utils/sql.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/src/classes/BaseModel.php';

use src\classes\BaseModel as BaseModel;

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
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getById($id)
    {
        try {
            $statement = $this->db->prepare(select_city_by_id);
            $statement->execute(array('id' => $id));
            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $category)
    {
        try {
            $statement = $this->db->prepare(insert_city);
            $statement->execute(array(
                'name' => $category['name'],
                'population'  => $category['population'],
                'size' => $category['size'],
                'capital' => $category['capital'],
            ));
            return $this->db->lastInsertID();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
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
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $statement = $this->db->prepare(delete_city);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}