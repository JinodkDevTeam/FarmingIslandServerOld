<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Server;

class TpAll extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("tpall", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("TpAll command");
        $this->setPermission("gh.tpall");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0]))
        {
            if (!$sender->hasPermission("gh.tpall"))
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
            foreach (Server::getInstance()->getOnlinePlayers() as $players)
            {
                $players->teleport(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel()));
            }
            $sender->sendMessage("All players have been teleported to ". $player->getName());
            return;
        }
        if (!$sender->hasPermission("gh.tpall"))
        {
            $sender->sendMessage("You not have permission to use this command");
            return;
        }
        $player = $sender;
        foreach (Server::getInstance()->getOnlinePlayers() as $players)
        {
            $players->teleport(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel()));
        }
        $sender->sendMessage("All player have been teleported to you");
    }
}