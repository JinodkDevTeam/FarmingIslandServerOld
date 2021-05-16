<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class Feed extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("feed", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Fees command");
        $this->setPermission("gh.feed");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0]))
        {
            if (!$sender->hasPermission("gh.feed.other"))
            {
                $sender->sendMessage("You not have permission to feed other player");
                return;
            }
            $player = Server::getInstance()->getPlayer($args[0]);
            if (!isset($player))
            {
                $sender->sendMessage("Player not exist !");
                return;
            }
            $player->setFood(20);
            $sender->sendMessage($player->getName() . "have been fed");
            return;
        }
        if (!$sender->hasPermission("gh.feed.use"))
        {
            $sender->sendMessage("You not have permission to use this command");
            return;
        }
        $sender->setFood(20);
        $sender->sendMessage("You have been fed !");

    }
}
