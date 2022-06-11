<?php

namespace App\Controllers;

class Businesses
{
    public static function connect_businesses_table($param, $param2) {
        $query = $param->query("SELECT * FROM `businesses` WHERE `bOwner` = '$param2'");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}