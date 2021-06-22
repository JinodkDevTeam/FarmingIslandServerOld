<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use NgLamVN\GameHandle\GameMenu\UpdateInfo;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;

class Event extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("event", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("View Event Info");
        $this->setPermission("gh.event");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        new UpdateInfo($sender, "event");
        return;
    }
}

