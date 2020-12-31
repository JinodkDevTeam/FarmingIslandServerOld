<?php

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;

class FiVersion extends PluginCommand
{

    public function __construct(Core $core)
    {
        parent::__construct("fiversion", $core);
        $this->setDescription("FarmingIsland Version");
        $this->setPermission("gh.fiver");
        $this->setAliases(["fiver", "fi-ver"]);
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $sender->sendMessage("Server version: 0.1.11-build3");
    }
}