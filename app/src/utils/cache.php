<?php

namespace src\utils;

use Predis;
use src\models\BaseModel;

class Cache
{
    private Predis\Client $redisConn;

    public function __construct()
    {
        $this->redisConn =  new Predis\Client([
            'scheme' => 'tcp',
            'host' => $_ENV["REDIS_HOST"],
            'port' => $_ENV['REDIS_PORT'],
        ]);
    }

    public function getConnection()
    {
        return $this->redisConn->getConnection();
    }

    public function get($key)
    {
        $redisData = $this->redisConn->get($key);
        if ($redisData != null) {
            $result = json_decode($redisData, TRUE);
        } else {
            $result = $redisData;
        }
        return $result;
    }

    public function set($key, $input) {
        $serializedData = json_encode($input);
        return $this->redisConn->set($key, $serializedData);
    }

    public function del($key) {
        return $this->redisConn->del($key);
    }
}