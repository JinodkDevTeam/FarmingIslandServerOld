<?php

namespace NgLamVN\GameHandle\task;

use onebone\economyapi\EconomyAPI;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class AfkRewardTask extends Task
{
    public function onRun(int $currentTick)
    {
        // TODO: Implement onRun() method.
        $players = Server::getInstance()->getOnlinePlayers();
        foreach ($players as $player)
        {
            if ($player->getLevel()->getName() == "afk")
            {
                EconomyAPI::getInstance()->addMoney($player, 500);
                $player->sendMessage("You have get 500xu in AFK Area !");
            }
        }
    }
}
