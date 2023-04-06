<?php

namespace src\utils\cache;

use Predis;

class cache
{
    private $redisConn = null;

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
}