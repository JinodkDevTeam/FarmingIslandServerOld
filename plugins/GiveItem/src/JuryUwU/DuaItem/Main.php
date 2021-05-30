<?php

namespace JuryUwU\DuaItem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!$sender instanceof Player)
        {
            $sender->sendMessage("Vào game mà dùng !");
            return true;
        }
        if (!$sender->hasPermission("duaitem.command"))
        {
        }

        return true;
    }
}
