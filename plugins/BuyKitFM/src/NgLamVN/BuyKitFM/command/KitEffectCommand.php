<?php

namespace NgLamVN\BuyKitFM\command;

use NgLamVN\BuyKitFM\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;

class KitEffectCommand extends PluginCommand
{
    public $plugin;

    public function __construct(Loader $plugin)
    {
        parent::__construct("kiteffect", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Kit Effect");
        $this->setPermission("buykitfm.kiteffect");
    }

    public function getLoader(): Loader
    {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (isset($args[0]))
        {
            if ($args[0] === "clear")
            {
                $sender->removeAllEffects();
                return;
            }
        }
        if (!isset($this->getLoader()->pd[$sender->getName()]))
        {
            $sender->sendMessage("You dont have any kit.");
            return;
        }
        $kit = $this->getLoader()->pd[$sender->getName()];
        $this->getLoader()->getKit($kit)->giveEffect($sender);
    }
}
