<?php

namespace App\Controllers;

class House extends App
{
    public static function connect_house_table($param, $param2) {
        $query = $param->query("SELECT * FROM `house` WHERE `hOwner` = '$param2'");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }


}