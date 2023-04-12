<?php

namespace src\classes;

use src\utils\Database as Database;

abstract class BaseModel
{
    protected Database $db;
    public $result;
    public $error;

    public function __construct($db)
    {
        $this->db = $db;
        $this->result = null;
        $this->error = null;
    }

    abstract public function getAll();
    abstract public function getById(int $id);
    abstract public function insert(Array $input);
    abstract public function update(int $id, Array $input);
    abstract public function delete(int $id);

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result): void
    {
        $this->result = $result;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error): void
    {
        $this->error = $error;
    }


}