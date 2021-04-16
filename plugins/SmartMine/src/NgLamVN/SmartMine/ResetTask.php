<?php

namespace NgLamVN\SmartMine;

use pocketmine\scheduler\Task;

class ResetTask extends Task
{
    /** @var Loader */
    public $loader;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    public function onRun(int $currentTick)
    {
        $this->loader->onReset();
    }
}