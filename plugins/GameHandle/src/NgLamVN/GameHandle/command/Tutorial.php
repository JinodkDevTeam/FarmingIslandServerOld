<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use NgLamVN\GameHandle\GameMenu\UpdateInfo;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class Tutorial extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("tutorial", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("View tutorial");
        $this->setPermission("gh.tutorial");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        new UpdateInfo($sender, "tutorial");
        return;
    }
}
