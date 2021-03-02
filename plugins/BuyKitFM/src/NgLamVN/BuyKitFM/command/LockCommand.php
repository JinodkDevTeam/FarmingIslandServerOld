<?php

namespace NgLamVN\BuyKitFM\command;

use NgLamVN\BuyKitFM\ItemLock\ItemLock;
use NgLamVN\BuyKitFM\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\plugin\Plugin;

class LockCommand extends PluginCommand
{
    public $plugin;

    public function __construct(Loader $plugin)
    {
        parent::__construct("itemlock", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Lock item");
        $this->setPermission("buykitfm.lock");
    }

    public function getLoader(): Loader
    {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("buykitfm.lock")) {
            $sender->sendMessage("You not have permission to use this command !");
            return;
        }
        if (!isset($args[0]))
        {
            $player = $sender;
        }
        else
        {
            $player = Server::getInstance()->getPlayer($args[0]);
        }
        if ($player === null)
        {
            $sender->sendMessage("This player is not online");
            return;
        }
        $item = $player->getInventory()->getItemInHand();
        if ($item->getNamedTag()->hasTag("lock"))
        {
            $item = ItemLock::unlock($item);
            $player->getInventory()->setItemInHand($item);
            return;
        }
        $item = ItemLock::lock($item, $player);
        $player->getInventory()->setItemInHand($item);
    }
}
