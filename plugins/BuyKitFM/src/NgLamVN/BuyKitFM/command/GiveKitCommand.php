<?php

namespace NgLamVN\BuyKitFM\command;

use NgLamVN\BuyKitFM\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\plugin\Plugin;

class GiveKitCommand extends PluginCommand
{
    public $plugin;

    public function __construct(Loader $plugin)
    {
        parent::__construct("givekit", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Give Kits");
        $this->setPermission("buykitfm.givekit");
    }

    public function getLoader(): Loader
    {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("buykitfm.givekit")) {
            $sender->sendMessage("You not have permission to use this command !");
            return;
        }
        if (!isset($args[0])) {
            $sender->sendMessage("/givekit <kit_name> <player>");
            return;
        }
        if (!isset($args[1])) {
            $player = $sender;
        } else {
            $player = Server::getInstance()->getPlayer($args[1]);
        }
        if ($player === null) {
            $sender->sendMessage("This player is not online");
            return;
        }
        if ($this->getLoader()->getKit($args[0]) === null) {
            $sender->sendMessage("Invalid Kit Name !");
            return;
        }
        $this->getLoader()->getKit($args[0])->giveItem($player);
        $this->getLoader()->pd[$player->getName()] = $args[0];
    }
}