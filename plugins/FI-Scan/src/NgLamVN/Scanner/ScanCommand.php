<?php

namespace NgLamVN\Scanner;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

class ScanCommand extends PluginCommand
{
    public Loader $loader;

    public function __construct(Loader $loader)
    {
        parent::__construct("scan", $loader);

        $this->loader = $loader;

        $this->setPermission("scan.start");

        $this->setDescription("Start Scan (Console only)");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!($sender instanceof ConsoleCommandSender))
        {
            $sender->sendMessage("Console use only !");
            return;
        }
        if (count(Server::getInstance()->getOnlinePlayers()) > 0)
        {
            $sender->sendMessage("0 player online is requied !");
            return;
        }
        $sender->sendMessage("Start Scanning ...");
        $this->loader->startScan();
    }

}