<?php

namespace App\Controllers;

class App
{
    public $connection;

    public function __construct()
    {
        $config = require_once "config/db.php";
        $this->connection = new \PDO("mysql:host=$config[host];dbname=$config[db]", "$config[user]", "$config[password]");
    }

}