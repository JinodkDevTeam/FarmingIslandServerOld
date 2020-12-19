<?php

namespace NgLamVN\GameHandle\task;

use NgLamVN\GameHandle\Core;

class InitTask
{
    public $core;

    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->init();
    }

    public function getCore(): ?Core
    {
        return $this->core;
    }

    public function init()
    {
        $this->getCore()->getScheduler()->scheduleRepeatingTask(new AfkRewardTask(), 200); //TODO: AfkRewardTask (10secs)
    }
}