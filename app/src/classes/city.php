<?php

namespace src\classes\city;

include $_SERVER['DOCUMENT_ROOT'] . '/src/utils/sql.php';

class City
{
    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        try {
            $statement = $this->db->query(select_all_cities);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            $statement = $this->db->prepare(select_city_by_id);
            $statement->execute(array($id));
            return $statement->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        try {
            $statement = $this->db->prepare(insert_city);
            $statement->execute(array(
                'name' => $input['name'],
                'population'  => $input['population'],
                'size' => $input['size'],
                'capital' => $input['capital'],
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