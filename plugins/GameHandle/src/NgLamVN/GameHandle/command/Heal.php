<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class Heal extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("heal", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Heal command");
        $this->setPermission("gh.heal");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0]))
        {
            if (!$sender->hasPermission("gh.heal.other"))
            {
                $sender->sendMessage("You not have permission to heal other player");
                return;
            }
            $player = Server::getInstance()->getPlayer($args[0]);
            if (!isset($player))
            {
                $sender->sendMessage("Player not exist !");
                return;
            }
            $player->setHealth(20);
            $sender->sendMessage($player->getName() . "have been healed");
            return;
        }
        if (!$sender->hasPermission("gh.heal.use"))
        {
            $sender->sendMessage("You not have permission to use this command");
            return;
        }
        $sender->setHealth(20);
        $sender->sendMessage("You have been healed !");
    }
}
