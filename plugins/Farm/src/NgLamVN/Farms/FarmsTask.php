<?php

namespace NgLamVN\Farms;

use pocketmine\scheduler\Task;

class FarmsTask extends Task {
    public $plugin;
    public function __construct(Farms $plugin) {
        $this->plugin = $plugin;
    }
    public function onRun(int $currentTick) {
        $this->plugin->tick();
    }
}