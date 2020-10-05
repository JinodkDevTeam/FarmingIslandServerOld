<?php

namespace NgLamVN\GameHandle\AchivementSystem\type;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class BreakType implements Listener
{
    public function onBreak (BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();

    }
}
