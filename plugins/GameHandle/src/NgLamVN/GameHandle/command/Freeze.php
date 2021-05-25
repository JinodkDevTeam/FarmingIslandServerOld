<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class Freeze extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("freeze", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Freeze command");
        $this->setPermission("gh.freeze");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0]))
        {
            if (!$sender->hasPermission("gh.freeze"))
            {
                $sender->sendMessage("You not have permission to use this command");
                return;
            }
            $player = Server::getInstance()->getPlayer($args[0]);
            if (!isset($player))
            {
                $sender->sendMessage("Player not exist !");
                return;
            }
            $time = PHP_INT_MAX;
            if (isset($args[1]))
            {
                if (is_numeric($args[1]))
                {
                    $time = (int) $args[1];
                }
                else
                {
                    $sender->sendMessage("Time must me numeric !");
                    return;
                }
            }
            $this->plugin->getPlayerStatManager()->getPlayerStat($player)->setFreeze(true, $time);
            $sender->sendMessage("Frozen " .$player->getName(). " for " .(string) $time. " seconds !");
            $player->sendMessage("You have been frozen for " .(string) $time. " seconds");
            return;
        }
        $sender->sendMessage("/freeze <player> <time>");
    }
}
