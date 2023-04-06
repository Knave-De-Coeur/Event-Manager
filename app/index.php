<?php

require_once __DIR__."/setup.php";

if ($redis->isConnected()) {
    echo "We connected to redis!";
}

phpinfo();