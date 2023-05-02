<?php

namespace Src\Models;

abstract class BaseModel
{
    protected $db;
    protected $result;
    protected $error;

    public function __construct($db)
    {
        $this->db = $db;
        $this->result = null;
        $this->error = null;
    }

    abstract public function getAll();
    abstract public function getById($id);
    abstract public function insert(Array $input);
    abstract public function update($id, Array $input);
    abstract public function delete($id);

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }


}