<?php

namespace App;

use App\VkAPi;
use App\Controllers\App;
use DigitalStars\SimpleVK\{Bot, SimpleVK as vk};

class Start extends App
{
    public function start() {
        $vkapi = new VkApi();

        $vkapi->confirm();
        $vkapi->cmd($this->connection);
    }
}