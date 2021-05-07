<?php

namespace jacknoordhuis\autoinv\command;

use jacknoordhuis\autoinv\AutoInv;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;

class AutoPickUp extends PluginCommand
{
    /** @var AutoInv */
    private $plugin;

    public function __construct(AutoInv $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("autopickup", $plugin);
        $this->setDescription("Auto pickup block drop mode");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($this->plugin->isAutoPickup($sender))
        {
            $this->plugin->setBlockPickupMode($sender, false);
            $sender->sendMessage("Disabled auto pickup block!");
        }
        else
        {
            $this->plugin->setBlockPickupMode($sender, true);
            $sender->sendMessage("Enabled auto pickup block!");
        }
    }
}