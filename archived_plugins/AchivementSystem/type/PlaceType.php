<?php

namespace NgLamVN\GameHandle\AchivementSystem\type;

use pocketmine\event\block\BlockPlaceEvent;

class PlaceType extends BaseType
{
    /**
     * @param BlockPlaceEvent $event
     * @priority HIGHEST
     */
    public function onPlace (BlockPlaceEvent $event)
    {
        if ($event->isCancelled())
        {
            return;
        }
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if (($this->getAchivement()->getItem()->getId()) == 0 OR ($this->getAchivement()->getItem()->getId() == $block->getId()))
        {
            $newcount = $this->getAManager()->getPlayerData($player->getName())->getCount($this->getAchivement()->getId()) + 1;
            $this->getAManager()->getPlayerData($player->getName())->setCount($this->getAchivement()->getId(), $newcount);
        }
    }
}
