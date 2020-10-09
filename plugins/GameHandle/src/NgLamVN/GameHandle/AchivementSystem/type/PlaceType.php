<?php

namespace NgLamVN\GameHandle\AchivementSystem\type;

use pocketmine\event\block\BlockPlaceEvent;

class PlaceType extends BaseType
{
    public function onPlace (BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if ($this->getAItem()->getId() == 0)
        {
            return;
        }
        if ($this->getAItem()->getId() == $block->getId())
        {
            return;
        }
    }
}
