<?php
    $connect = mysqli_connect('','','',''); // connect bd

    require_once __DIR__.'/vendor/digitalstars/simplevk/autoload.php';
    use DigitalStars\SimpleVK\{Bot, SimpleVK as vk};

    $vk = vk::create('', '5.126')->setConfirm('');  // vk api
    $bot = Bot::create('', '5.126'); // vk api
    
    $vk->setUserLogError('582127671');
    $data = $vk->initVars($peer_id, $user_id, $type, $message); //инициализация переменных из события

    if(!$connect) {
        $vk->reply('Ошибка подключение к БД'); // check connect bd
    }

    if($type == 'message_new') {
        if($message == '!команды') { // cmd 
            $vk->reply('
                Доступные команды 
                - !админы
                - !лидеры
                - !онлайн
                - !прокачать {nickname}
            ');
        }
        if($message == '!админы') { // cmd
            $admin = mysqli_query($connect, "SELECT * FROM `s_admin`"); // connecting to the admin database

            while ($row = mysqli_fetch_assoc($admin)) {
                $vk->reply('
                    НикНейм: '.$row['Name'].'
                    Последний заход: '.date('Y-m-d', $row['LastCon']).'
                    Отвечено репортов: '.$row['s_Reports'].'

                    Выдано блокировок: '.$row['s_Bans'].'
                    Выдано варнов: '.$row['s_Warns'].'
                    Выдано мутов: '.$row['s_Mutes'].'
                    Выдано дмг: '.$row['s_Prisons'].'
                ');
            }
        }

        if($message == '!лидеры') {
            $leaders = mysqli_query($connect, "SELECT * FROM `s_fraction`");

            $vk->reply('‼ Показаны только те лидеры, которых поставили! ‼');

            while ($row = mysqli_fetch_assoc($leaders)) {
                if($row['fLeader'] != 'None'){
                    $vk->reply('
                        НикНейм: '.$row['fLeader'].'
                        Заместитель: '.$row['fAssistant'].'
                        Сообщение при заходе: '.$row['fMessage'].'
                    ');
                }
            }
        }

        if($message == '!онлайн') {
            $online = mysqli_query($connect, "SELECT count(*) FROM `s_users` WHERE `pLogin` = 1");
            $online = mysqli_fetch_row($online);

            $vk->reply('Онлайн на данный момент: '. $online[0] .' человек');
        }

    }

    $bot->preg_cmd('update', '!\!прокачать (.*)!')->func(function ($msg, $params) use ($connect){
        $account = mysqli_query($connect, "SELECT * FROM `s_users` WHERE `Name` = '$params[1]'");
        $account = mysqli_fetch_array($account);

        if($account) {
            $money = $account['pCash'] + 30000;
            $mats = $account['pDrug'] + 30000;
            $drugs = $account['pDrug'] + 30000;

            mysqli_query($connect, "
                UPDATE `s_users` SET `pMats`= $mats,`pCash`= $money,`pDrug`= $drugs WHERE `Name` = '$params[1]'
            ");

            $msg->text("Аккаунт $params[1] успешно прокачен");
        } else {
            $msg->text("‼ Аккаунт не найден");
        }
    });

    $bot->preg_cmd('info', '!\!профиль (.*)!')->func(function ($msg, $params) use ($connect){
        $account = mysqli_query($connect, "SELECT * FROM `s_users` WHERE `Name` = '$params[1]'");
        $account = mysqli_fetch_array($account);

        if($account) {
            $msg->text('
                🙍‍♂ НикНейм: '.$params[1].'
                Уровень: '.$account['pLevel'].'
                Деньги на руках: '.number_format($account['pCash']).'
                Наркотики: '.number_format($account['pDrug']).'
                Материаллы: '.number_format($account['pMats']).'
            ');
        } else {
            $msg->text("‼ Аккаунт не найден");
        }
    });

    $bot->run();