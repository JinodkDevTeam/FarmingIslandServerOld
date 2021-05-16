<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\command\PluginCommand;

class Gm1 extends PluginCommand
{
    public function __construct(Core $plugin)
    {
        parent::__construct("gm1", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Game mode command");
        $this->setPermission("gh.gm1");
        $this->setAliases(["gmc"]);
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0]))
        {
            if (!$sender->hasPermission("gh.gm1.other"))
            {
                $sender->sendMessage("You not have permission to set game mode other player");
                return;
            }
            $player = Server::getInstance()->getPlayer($args[0]);
            if (!isset($player))
            {
                $sender->sendMessage("Player not exist !");
                return;
            }
            $player->setGamemode(1);
            $sender->sendMessage($player->getName() . " changed game mode to creative");
            return;
        }
        if (!$sender->hasPermission("gh.gm1.use"))
        {
            $sender->sendMessage("You not have permission to use this command");
            return;
        }
        $sender->setGamemode(1);
        $sender->sendMessage("Your game mode have changed to creative !");
    }
}