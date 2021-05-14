<?php

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class ReloadSkin extends PluginCommand
{
    private $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("reloadskin", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Reload All Player Skin");
        $this->setPermission("gh.reloadskin");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("gh.reloadskin"))
        {
            $sender->sendMessage("You not have permission to use this command !");
            return;
        }

        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player)
        {
            $player->setSkin($this->plugin->skin[$player->getName()]);
        }
        $sender->sendMessage("All Player Skin reloaded !");
    }
}