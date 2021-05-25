<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class UnMute extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("unmute", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("UnMute command");
        $this->setPermission("gh.unmute");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0]))
        {
            if (!$sender->hasPermission("gh.unmute"))
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

            $this->plugin->getPlayerStatManager()->getPlayerStat($player)->setMute(false);
            $sender->sendMessage("Unmuted " .$player->getName(). " !");
            $player->sendMessage("You have been unmuted !");
            return;
        }
        $sender->sendMessage("/unmute <player>");
    }
}
