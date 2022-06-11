<?php

namespace App\Controllers;

class Promocodes extends App
{

    public static function connect_base($param, $param2) {
        $query = $param->query("SELECT * FROM `s_promocodes` WHERE `codeName` = '". self::seperation($param2)[0] ."'");
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

    public static function create($param, $param2) {
        if (self::checked_array($param2) === true) {
            if (self::filter_promocode($param, $param2) === true) {
                if (self::create_promocode($param, $param2) === true) {
                    return "✔ Промокод " . self::seperation($param2)[0] . " с владельцем " . self::seperation($param2)[1] . " успешно создан";
                } else {
                    return "❌ Случился баг -_-";
                }
            } else {
                return "❌ Промокод " . self::seperation($param2)[0] . " уже существует";
            }
        } else {
            return "❌ Ввели некорректное значение";
        }
    }

    public static function delete($param, $param2) {
        if (self::filter_promocode($param, $param2) === false) {
            if (self::delete_promocode($param, $param2) === true) {
                return "✔ Промокод $param2 успешно удален";
            } else {
                return "❌ Случился баг -_-";
            }
        } else {
            return "❌ Промокод ".self::seperation($param2)[0]." не существует";
        }
    }

    public static function info($param, $param2) {
        if (self::filter_promocode($param, $param2) === false) {
            return self::info_promocode($param, $param2);
        } else {
            return ["❌ Промокод ".self::seperation($param2)[0]." не существует"];
        }
    }

    public static function edit($param, $param2) {
        if (self::checked_array($param2) === true) {
            if (self::filter_promocode($param, $param2) === false) {
                if (self::edit_promocode($param, $param2) === true) {
                    return "✔ Владелец промокода - ". self::seperation($param2)[0]." успешно изменен на ". self::seperation($param2)[1] ."";
                } else {
                    return "❌ Случился баг -_-";
                }

            } else {
                return "❌ Промокод ".self::seperation($param2)[0]." не существует";
            }
        } else {
            return "❌ Ввели некорректное значение";
        }
    }

    public static function filter_promocode($param, $param2) {
        if (!self::connect_base($param, $param2)) {
            return true;
        } else {
            return false;
        }
    }

    public static function create_promocode($param, $param2) {
        $create = $param->prepare("INSERT INTO `s_promocodes` (`promoID`, `codeID`, `codeName`, `codeOwner`, `codeDate`, `codeItem`, `codeUsed`, `codeCurrentLvl4`, `codeCurrentLvl10`, `codeCurrentLvl20`) VALUES ('0', '". rand(0, 1000)."', '". self::seperation($param2)[0] ."', '".self::seperation($param2)[1]."','". date('d/m/Y')."', '-1', '0', '0', '0', '0')");
        $create->execute();

        return true;
    }

    public static function delete_promocode($param, $param2) {
        $delete = $param->exec("DELETE FROM `s_promocodes` WHERE `codeName` = '$param2'");
        return true;
    }

    public static function info_promocode($param, $param2) {
        $promo = [];
        foreach (self::connect_base($param, $param2) as $value) {
            $promo = [
                "Промокод: ".$value['codeName']."",
                "-Владелец: ".$value['codeOwner']."",
                "-Всего использований: ".$value['codeUsed'].""
            ];
        }

        return $promo;
    }

    public static function edit_promocode($param, $param2) {
        $promo = $param->exec("UPDATE `s_promocodes` SET `codeOwner` = '".self::seperation($param2)[1]."' WHERE `codeName` = '".self::seperation($param2)[0]."'");  // 1 - namepromocode, new

        return true;
    }
}