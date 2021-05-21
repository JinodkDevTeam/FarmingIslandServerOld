<?php

namespace NgLamVN\Scanner;

use NgLamVN\Scanner\type\IslandScanner;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase
{
    public function onEnable()
    {
        $this->getServer()->getCommandMap()->register("scan", new ScanCommand($this));
    }

    public function startScan()
    {
        for ($x = -10; $x <= 10; $x++)
            for ($y = -10; $y <= 10; $y++)
            {
                new IslandScanner($this, $x, $y);
            }
        $this->getLogger()->info("Island SCAN COMPLETE !");
    }
}
