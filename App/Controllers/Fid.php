<?php

namespace App\Controllers;

use App\Controllers\Leader;

class Fid extends App
{
    public static function connect_fraction_tabe($param) {
        $query = $param->query("SELECT * FROM `s_fraction`");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function fid($param) {
        $fid = [];

        foreach (self::connect_fraction_tabe($param) as $value) {
//            $fid[] = array(
//                'ИД' => $value['fID'],
//                'Фракция' => $value['fName'],
//            );
            $fid[] = "[$value[fID]] - $value[fName]";
        }

        return $fid;
    }

}