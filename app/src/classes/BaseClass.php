<?php

namespace src\classes\BaseClass;

abstract class BaseClass
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    abstract public function getAll();
    abstract public function getById(int $id);
    abstract public function insert(Array $input);
    abstract public function update(int $id, Array $input);
    abstract public function delete(int $id);
}