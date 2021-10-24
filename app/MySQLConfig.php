<?php

namespace App;

use PDO;

abstract class MySQLConfig
{
    protected function connect(): PDO
    {
        $configJSON = json_decode(file_get_contents('config.json'), true);

        $host = $configJSON['host'];
        $user = $configJSON['user'];
        $password = $configJSON['password'];
        $database = $configJSON['database'];

        $dsn = "mysql:host={$host};dbname={$database};charset=UTF8";

        return new PDO($dsn, $user, $password);
    }
}