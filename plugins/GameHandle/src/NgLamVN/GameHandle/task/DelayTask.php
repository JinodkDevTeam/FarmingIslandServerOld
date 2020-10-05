<?php

namespace NgLamVN\GameHandle\task;

use NgLamVN\GameHandle\Core;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use Closure;

class DelayTask extends Task
{
    public $core;
    public $closure;
    public $times;
    public $player;

    public function __construct(int $delaytick, $closure, Core $core, Player $player)
    {
        $core->getScheduler()->scheduleRepeatingTask($this, $delaytick);
        $this->core = $core;
        $this->closure = $closure;
        $this->times = 0;
        $this->player = $player;
    }

    public function getCore(): Core
    {
        return $this->core;
    }
    public function getClosure()
    {
        return $this->closure;
    }

    public function onRun(int $currentTick)
    {
        // TODO: Implement onRun() method.
        if ($this->times = 0)
        {
            $this->times = 1;
            return;
        }
        $run = $this->closure;
        $run();
        $this->getCore()->getScheduler()->cancelTask($this->getTaskId());
    }
}
