<?php

namespace App\Controllers;

use App\Controllers\House;


class Users
{
    public static function connect_base($param, $param2) {
        $query = $param->query("SELECT * FROM `s_users` WHERE `Name` = '$param2'");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function filter($param, $param2) {
        if (!self::connect_base($param, $param2)) {
            return false;
        } else {
            return true;
        }
    }

    public static function unification($param, $param2) {
        if(self::filter($param, $param2) === true) {
            foreach (self::connect_base($param, $param2) as $value) {
                $users = [
                    "НикНейм => $value[Name]",
                    "Уровень => $value[pLevel]",
                    "-----------",
                    "Гугл защита => ".self::google($param, $param2)."",
                    "-----------",
                    "Донат счет => ".number_format($value['u_donate'])."",
                    "Депозит счет => ".number_format($value['pDeposit'])."",
                    "Банковский счет => ".number_format($value['pBank'])."",
                    "Наличные => ".number_format($value['pCash'])."",
                    '----------',
                    "Организация - ".self::filter_members($param, $value['pMember'])."",
                    "Ранг - $value[pRank] ранг",
                    "Работа - ". self::filter_job($value['pJob'])."",
                    "-----------",
                    "Дом - ". self::filter_house($param, $value['Name'])."",
                    "Бизнес - ". self::filter_businesses($param, $value['Name'])."",
                ];
            }

            return $users;
        } else {
            return [
                'Аккаунт не найден'
            ];
        }
    }

    public static function google($param, $param2) {
        foreach (self::connect_base($param, $param2) as $item) {
            if ($item['Google'] == 1) {
                return "имеется";
            } else {
                return "отключена";
            }
        }
    }

    public static function connect_base_fraction($param, $param2)
    {
        if (Member::filter($param2) === true) {
            $query = $param->query("SELECT * FROM `s_fraction` WHERE `fID` = $param2");
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public static function filter_members($param, $param2) {
        if (self::connect_base_fraction($param, $param2) === true) {
            foreach (self::connect_base_fraction($param, $param2) as $item) {
                $users = [
                    $item['fName']
                ];
            }
            return $users;
        } else {
            if ($param2 == 0) {
                return "отсуствует";
            } else {
                return "Error";
            }
        }
    }

    public static function filter_job($param) {
        if ($param == 0) {
            return "отсуствует";
        } else {
            return $param;
        }
    }

    public static function filter_house($param, $param2) {
        if (House::connect_house_table($param, $param2)) {
            foreach (House::connect_house_table($param, $param2) as $item) {
                $house = "$item[hID] ID";
            }

            return $house;
        } else {
            return "отсуствует";
        }
    }

    public static function filter_businesses($param, $param2) {
        if(Businesses::connect_businesses_table($param, $param2)) {
            foreach (Businesses::connect_businesses_table($param, $param2) as $item) {
                $businesses = "$item[bName]";
            }

            return $businesses;
        } else {
            return "отсуствует";
        }
    }
}
