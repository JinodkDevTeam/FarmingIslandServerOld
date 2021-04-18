<?php

namespace malgn\CustomBreakTimeAPI;

use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class BreakTask extends Task
{
    protected $player;
    protected $pos;
    protected $api;

    public function __construct(Player $player, Vector3 $pos, CustomBreakTimeAPI $api)
    {
        $this->player = $player;
        $this->pos = $pos;
        $this->api = $api;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getPos(): Vector3
    {
        return $this->pos;
    }

    public function getAPI(): CustomBreakTimeAPI
    {
        return $this->api;
    }

    public function onRun(int $currentTick)
    {
        if (!$this->getAPI()->isBreaking($this->player)) return;
        $item = $this->getPlayer()->getInventory()->getItemInHand();
        CustomBreakTimeAPI::getBaseBreakTime($item)->onBreak($this->pos, $this->player, $item);
    }

    public function cancel()
    {
        $this->getAPI()->getScheduler()->cancelTask($this->getTaskId());
    }
}
