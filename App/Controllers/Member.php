<?php

namespace App\Controllers;

class Member
{
    public static function filter($param) {
        if ($param > 25 or $param < 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function find($param, $db) {
        $query = $db->query("SELECT * FROM `s_users` WHERE `pMember` = $param");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function unification($param, $db) {
        if(self::filter($param) === true) {
            $member = [];

            foreach (self::find($param, $db) as $item) {
                $member[] = "$item[Name] - $item[pRank] ранг | " . self::status($param, $db) . "";
            }

            return $member;
        } else {
            return [
                'Вы ввели некорректное значение'
            ];
        }
    }

    public static function status($param, $db) {
        foreach (self::find($param, $db) as $item) {
            if($item['pLogin'] == 1) {
                return '|✔|';
            } else {
                return '[✖]';
            }
        }
    }
}