<?php

namespace malgn\CustomBreakTimeAPI;

use malgn\test\Test;
use pocketmine\item\Item;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class CustomBreakTimeAPI extends PluginBase
{
    /** @var BaseBreakTime[] $data */
    public static $data = [];
    /** @var bool[] */
    protected $breaking = [];

    public function isBreaking(Player $player): bool
    {
        if (!isset($this->breaking[$player->getName()])) return false;
        else return $this->breaking[$player->getName()];
    }

    public function setBreakStatus(Player $player, bool $status)
    {
        $this->breaking[$player->getName()] = $status;
    }

    public static function register(BaseBreakTime $baseBreakTime)
    {
        $name = $baseBreakTime->getName();
        if (isset(self::$data[$name])) throw new \Exception("A BaseBreakTime config with that name aldready registered !");
        self::$data[$name] = $baseBreakTime;
    }

    public static function getBaseBreakTime(Item $item): ?BaseBreakTime
    {
        $nbt = $item->getNamedTag();
        if (!$nbt->hasTag("basebreaktime")) return null;
        $config = $nbt->getString("basebreaktime");

        if (isset(self::$data[$config]))
        {
            return self::$data[$config];
        }
        else return null;
    }

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);
    }
}