<?php

namespace NgLamVN\Dev;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;

class TestCMD extends PluginCommand
{
    public $plugin;

    public function __construct(Test $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("test", $plugin);

        $this->setDescription("TEST TEST TEST");
        $this->setPermission("ngl.dev.test");
    }

    public function getLoader():Test
    {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("ngl.dev.test"))
        {
            $sender->sendMessage("DONT USE THIS COMMAND, WILL CRASH THE SERVER !!!!!!!!");
            return;
        }
    }
}