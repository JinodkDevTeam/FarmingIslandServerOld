<?php

namespace NgLamVN\GameHandle\AchivementSystem\command;

use NgLamVN\GameHandle\AchivementSystem\form\AchivementForm;
use NgLamVN\GameHandle\Core;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class AchivementCommand extends PluginCommand
{
    public function __construct(Core $core)
    {
        parent::__construct("achievement", $core);
        $this->setDescription("Achievement Command");
        $this->setAliases(["ac", "am"]);
        $this->setPermission("gh.ac");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!($sender instanceof Player))
        {
            return;
        }
        new AchivementForm($sender);
    }
}
