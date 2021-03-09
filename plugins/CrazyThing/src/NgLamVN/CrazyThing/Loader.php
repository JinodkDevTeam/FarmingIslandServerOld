<?php

namespace NgLamVN\CrazyThing;

use NgLamVN\CrazyThing\provider\YamlProvider;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase
{
    public $provider;

    public function onLoad()
    {
        $this->provider = new YamlProvider();
    }

    public function onEnable()
    {
        $this->getProvider()->open($this->getDataFolder());
    }

    public function getProvider(): YamlProvider
    {
        return $this->provider;
    }

    public function isBanned (Player $player)
    {
        if (isset($this->getProvider()->getAllBanData()[$player->getName()]))
        {
            $time = $this->getProvider()->getAllBanData()[$player->getName()]["time"];
            $current = time();
            if ($current > $time)
            {
                return true;
            }
        }
        return false;
    }
}