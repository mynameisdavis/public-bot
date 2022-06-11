<?php

namespace App\Controllers;

class Leader extends App
{
    public function start() {
        self::list_leaders($this->connection);
    }

    public static function connect_base_leaders($param) {
        $query = $param->query("SELECT * FROM `s_fraction` WHERE `fLeader` != 'None'");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }


    public static function standart_list_leaders($param) {
        $leader = [];

        foreach (self::connect_base_leaders($param) as $connect_base_leader) {
            $leader[] = "$connect_base_leader[fLeader] - $connect_base_leader[fName] | Отыграно - ". round((Admin::search_users($param, $connect_base_leader['fLeader']) / 60) /60)."ч | ". Admin::check_time(round((Admin::search_users($param, $connect_base_leader['fLeader']) / 60) /60)) ."";
        }

        return $leader;
    }



}