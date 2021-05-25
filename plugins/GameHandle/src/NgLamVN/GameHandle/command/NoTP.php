<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;

class NoTP extends PluginCommand
{
    private Core $plugin;

    public function __construct(Core $plugin)
    {
        parent::__construct("notp", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("NoTP Mode command");
        $this->setPermission("gh.notp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player)
        {
            $sender->sendMessage("Use ingame only !");
            return;
        }
        if ($this->plugin->getPlayerStatManager()->getPlayerStat($sender)->isNoTP())
        {
            $this->plugin->getPlayerStatManager()->getPlayerStat($sender)->setNoTP(false);
            $sender->sendMessage("NoTP disabled !");
        }
        else
        {
            $this->plugin->getPlayerStatManager()->getPlayerStat($sender)->setNoTP();
            $sender->sendMessage("NoTP enabled !");
        }
    }
}
