<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Server;

class Haste extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("haste", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("Haste Effect");
        $this->setPermission("gh.haste.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!isset($args[0]))
        {
            $sender->sendMessage("/haste <level (1-5)> <player>");
            return;
        }
        if (!is_integer($args[0]))
        {
            $sender->sendMessage("Level must be on integer type");
            return;
        }
        $level = $args[0];
        $effect = new EffectInstance(Effect::getEffect(Effect::HASTE), 9999999, $level, true);

        if (isset($args[1])) {
            if (!$sender->hasPermission("gh.haste.other")) {
                $sender->sendMessage("You not have permission to enable haste on other player");
                return;
            }
            $player = Server::getInstance()->getPlayer($args[1]);
            if (!isset($player)) {
                $sender->sendMessage("Player not exist !");
                return;
            }
            if ($player->hasEffect(Effect::HASTE))
            {
                $player->removeEffect(Effect::HASTE);
                $sender->sendMessage("Disable haste on " . $player->getName());
                return;
            }
            $player->addEffect($effect);
            $sender->sendMessage("Enable haste on " . $player->getName());
            return;
        }
        if (!$sender->hasPermission("gh.haste.use")) {
            $sender->sendMessage("You are not have permission to use this command");
            return;
        }

        if ($sender->hasEffect(Effect::HASTE))
        {
            $sender->removeEffect(Effect::HASTE);
            $sender->sendMessage("Haste Disabled !");
        }
        $sender->addEffect($effect);
        $sender->sendMessage("Haste Enabled !");
    }
}

