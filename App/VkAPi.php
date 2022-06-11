<?php

namespace App;

use App\Controllers\Admin;
use App\Controllers\App;
use App\Controllers\Cmd;
use App\Controllers\Fid;
use App\Controllers\Leader;
use App\Controllers\Member;
use App\Controllers\Promocodes;
use App\Controllers\Security;
use App\Controllers\Servers;

use App\Controllers\Users;
use DigitalStars\SimpleVK\{Bot, SimpleVK as vk};

class VkAPi extends App
{
    public $vks;
    public $bot;
    public $cmd;


    public function __construct()
    {
        $config = require_once "config/vk.php";

        $this->vks = vk::create($config['token'], $config['version'])->setConfirm($config['confirm']);
        $this->bot = Bot::create($config['token'], $config['version']);

        $this->cmd = [
            '!статистика', '!админы', '!лидеры'
        ];

    }

    public function confirm() {
        return $this->vks;
    }

    /**
     * @throws \DigitalStars\SimpleVK\SimpleVkException
     */

    public function cmd($param) {

        $this->bot->preg_cmd('cmdlist', '!\!команды!')->func(function ($msg) use ($param) {
            $msg->text(implode(PHP_EOL, Cmd::cmd_list()));
        });

        $this->bot->preg_cmd('stats', '!\!статистика!')->func(function ($msg) use ($param) {
            $msg->text(implode(PHP_EOL, Servers::unification($param)));
        });

        $this->bot->preg_cmd('fidlist', '!\!fid!')->func(function ($msg) use ($param) {
            $msg->text(implode(PHP_EOL, Fid::fid($param)));
        });

        $this->bot->preg_cmd('adminlist', '!\!админы!')->func(function ($msg) use ($param) {
            $msg->text(implode(PHP_EOL, Admin::list_admin($param)));
        });

        $this->bot->preg_cmd('leaderlist', '!\!лидеры!')->func(function ($msg) use ($param) {
            $msg->text(implode(PHP_EOL, Leader::standart_list_leaders($param)));
        });

        $this->bot->preg_cmd('memberslist', '!\!мемберс (.*)!')->func(function ($msg, $params) use ($param) {
            $msg->text(implode(PHP_EOL, Member::unification($params[1], $param)));
        });

        $this->bot->preg_cmd('accountfind', '!\!аккаунт (.*)!')->func(function ($msg, $params) use ($param) {
            $msg->text(implode(PHP_EOL, Users::unification($param, $params[1])));
        });

        $this->bot->preg_cmd('createpromocode', "!\!createpromocode (.*)!")->func(function ($msg, $params) use ($param){
            $msg->text(Promocodes::create($param, $params[1]));
        });

        $this->bot->preg_cmd('deletepromocode', '!\!deletepromocode (.*)!')->func(function ($msg, $params) use ($param){
            $msg->text(Promocodes::delete($param, $params[1]));
        });

        $this->bot->preg_cmd('checkpromo', '!\!checkpromo (.*)!')->func(function ($msg, $params) use ($param){
            $msg->text(implode(PHP_EOL, Promocodes::info($param, $params[1])));
        });

        $this->bot->preg_cmd('editpromo', '!\!editpromo (.*)!')->func(function ($msg, $params) use ($param){
            $msg->text(Promocodes::edit($param, $params[1]));
        });

        $this->bot->preg_cmd('resetguard', '!\!resetsecurity (.*)!')->func(function ($msg, $params) use ($param){
            $msg->text(Security::change_gogle($param, $params[1]));
        });

        $this->bot->preg_cmd('change_password', '!\!changepassword (.*)!')->func(function ($msg, $params) use ($param){
           $msg->text(Security::change_password($param, $params[1]));
        });

//        var_dump(Security::change_password($param, "norrthh 123123"));

        $this->bot->run();
    }

}

