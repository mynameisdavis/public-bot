<?php

namespace App\Controllers;

class Security
{

    public static function connection_table($param, $param2) {
        $query = $param->query("SELECT * FROM `s_users` WHERE `Name` = '". self::seperation($param2)[0] ."'");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function seperation($param)
    {
        return explode(" ", $param);
    }

    public static function checked_array($param) {
        if (count(self::seperation($param)) > 2 or count(self::seperation($param)) < 2) {
            return false;
        } else {
            return true;
        }
    }

    public static function checked_users($param, $param2) {
        if (self::connection_table($param, $param2)) {
            return true;
        } else {
            return false;
        }
    }

    public static function change_password($param, $param2) {
        if (self::checked_array($param2) === true) {
            if (self::checked_users($param, $param2)) {
                if (self::change_password_users($param, $param2) === true) {
                    return "✔ На аккунте - ". self::seperation($param2)[0]." пароль успешно изменен на ". self::seperation($param2)[1] ."";
                } else {
                    return "❌ BUG";
                }
            } else {
                return "❌ Аккаунт - ". self::seperation($param2)[0]." не найден";
            }
        } else {
            return "❌ Ввели некорректные значения";
        }
    }

    public static function change_gogle($param, $param2) {
        if (self::checked_users($param, $param2)) {
            if (self::remove_google($param, $param2) === true) {
                return "✔ На аккунте - ". self::seperation($param2)[0]." гугл успешно был снят";
            } else {
                return "❌ BUG";
            }
        } else {
            return "❌ Аккаунт - ". self::seperation($param2)[0]." не найден";
        }
    }

    public static function remove_google($param, $param2) {
        $users = $param->exec("UPDATE `s_users` SET `Google` = 0, `GooglePassword` = 'None' WHERE `Name` = '". self::seperation($param2)[0] ."'");

        return true;
    }

    public static function change_password_users($param, $param2) {
        $users = $param->exec("UPDATE `s_users` SET `pKey` = '".md5(md5(self::seperation($param2)[1])) ."' WHERE `Name` = '". self::seperation($param2)[0] ."'");

        return true;
    }
}