<?php

namespace NgLamVN\RankColor;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;

use _64FF00\PureChat\PureChat;
use _64FF00\PurePerms\PurePerms;

use NgLamVN\RankColor\EventListener;

class RankColor extends PluginBase
{
    public $pc, $pp;

    public function getPC(): ?PureChat
    {
        return $this->pc;
    }

    public function getPP(): ?PurePerms
    {
        return $this->pp;
    }


    public function onEnable()
    {
        $this->pc = $this->getServer()->getPluginManager()->getPlugin("PureChat");
        if (!isset($this->pc))
        {
            $this->getLogger()->alert("Please Install PureChat !");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
        $this->pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
        if (!isset($this->pp))
        {
            $this->getLogger()->alert("Please Install PurePerms !");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register("rankcolor", new RCcommand($this));
    }

    public function setDefaultColor(Player $player)
    {
        if ($this->getPPgroupname($player) == "Guest")
        {
            $this->setColor($player, "");
            return;
        }
        $group = $this->getPPgroupname($player);
        switch ($group)
        {
            case "Vip":
                $this->setColor($player, "§f|§l§6VIP§r§f|");
                break;
            case "VipPlus":
                $this->setColor($player, "§f|§l§6VIP§a+§r§f|");
                break;
            case "Staff":
                $this->setColor($player, "§f|§l§bStaff§r§f|");
                break;
            case "Admin":
                $this->setColor($player, "§f|§l§eAdmin§r§f|");
                break;
            case "Youtuber":
                $this->setColor($player, "§f|§l§cYou§ftuber§r§f|");
                break;
            case "Member":
                $this->setColor($player, "§f|§l§bMember§r§f|");
                break;
        }


    }

    public function setColor(Player $player, $rank)
    {
        $this->getPC()->setSuffix($rank, $player);
    }

    public function getColor(Player $player)
    {
        return $this->getPC()->getSuffix($player);
    }

    public function getPPgroupname (Player $player)
    {
        return $this->getPP()->getUserDataMgr()->getGroup($player)->getName();
    }
}
