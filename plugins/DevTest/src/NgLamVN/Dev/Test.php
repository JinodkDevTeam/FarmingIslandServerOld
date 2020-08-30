<?php

namespace NgLamVN\Dev;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Test extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->getServer()->getCommandMap()->register("test", new TestCMD($this));
    }

    public function TestFunction()
    {
        return;
    }
}