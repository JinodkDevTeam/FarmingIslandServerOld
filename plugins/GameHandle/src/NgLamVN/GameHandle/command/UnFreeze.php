<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class UnFreeze extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("unfreeze", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("UnFreeze command");
        $this->setPermission("gh.unfreeze");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0]))
        {
            if (!$sender->hasPermission("gh.unfreeze"))
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

            $this->plugin->getPlayerStatManager()->getPlayerStat($player)->setFreeze(false);
            $sender->sendMessage("Unfreeze " .$player->getName(). " !");
            $player->sendMessage("You have been unfreeze !");
            return;
        }
        $sender->sendMessage("/unfreeze <player>");
    }
}
