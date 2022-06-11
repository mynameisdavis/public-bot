<?php

namespace App\Controllers;

use App\Controllers\App;

class Servers extends App
{

    public static function admin_list($param) {
        $query = $param->query('SELECT * FROM `s_admin`');
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function admin_online_count($param) {
        $online_list = [];

        foreach (self::admin_list($param) as $item) {
            $users = $param->query("SELECT * FROM `s_users` WHERE `Name` = '$item[Name]' AND `pLogin` = 1");

            foreach ($users->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $online_list[] = 1;
            }
        }
        return array_sum($online_list);
    }

    public static function current_online($param) {
        return $param->query("SELECT count(*) FROM `s_users` WHERE `pLogin` = 1")->fetchColumn();

    }

//    public static function best_online_day($param) {
//        $get_online = $param->query("SELECT MAX(`start`) FROM `onlinecontroller` WHERE `dateday` = date('d') AND `datemonth` = date('m') AND `dateyear` = date('Y')")->fetchColumn();
////        $data = $param->query("SELECT * FROM `onlinecontroller` WHERE `start` = $get_online AND `dateday` = date('d') AND `datemonth` = date('m') AND `dateyear` = date('Y')");
////
////        foreach ($data as $datum) {
////            $date = "Пик: $datum[start]. Время - $datum[dateday]-$datum[datemonth]-$datum[dateyear] | $datum[datehour]:00";
////        }
//
//        var_dump($get_online);
//    }

    public static function unification($param) {
        return [
            "Статистика сервера Zalupa RolePlay | Odin:" .
            "\n".
            "\n".
            'Количество администраторов в сети:  '. self::admin_online_count($param) .''.
            "\n".
            "\n".
            'Количество игроков в сети: '.self::current_online($param).'/1000   '
        ];


    }

}