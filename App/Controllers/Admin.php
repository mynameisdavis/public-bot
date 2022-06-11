<?php

namespace App\Controllers;

use App\Controllers\App;

class Admin extends App
{

    public static function connect_base_admin($param) {
        $query = $param->query("SELECT * FROM `s_admin`");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function search_users($param, $name) {
        echo $param->query("SELECT `InGameDay` FROM `s_users` WHERE `Name` = '$name'")->fetchColumn();
    }


    public static function list_admin($param) {
        $admin = [];

        foreach (self::connect_base_admin($param) as $value) {
            $admin[] = "$value[Name] | Последний заход - ".date('Y-m-d', $value['LastCon'])." | Отыграно - ". round((self::search_users($param, $value['Name']) / 60)/60)."ч | ". self::check_time(round((self::search_users($param, $value['Name']) / 60)/60))."";
        }
        return $admin;
    }

    public static function check_time($param) {
        if($param >= 3) {
            return '|✔|';
        } else {
            return '[✖]';
        }
    }

}