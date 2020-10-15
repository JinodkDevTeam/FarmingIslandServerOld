<?php

namespace NgLamVN\GameHandle\AchivementSystem\type;

use pocketmine\event\player\PlayerFishEvent;

class FishType extends BaseType
{
    /**
     * @param PlayerFishEvent $event
     * @priority HIGHEST
     */
    public function onFish(PlayerFishEvent $event)
    {
        if ($event->isCancelled())
        {
            return;
        }
        if ($event->getState() !== PlayerFishEvent::STATE_CAUGHT_FISH)
        {
            return;
        }
        $player = $event->getPlayer();
        $loot = $event->getItemResult();
        foreach ($loot as $item)
            if ((($this->getAchivement()->getItem()->getId()) == 0) OR ($this->getAchivement()->getItem()->getId() == $item->getId()))
            {
                $count = $item->getCount();
                $newcount = $this->getAManager()->getPlayerData($player->getName())->getCount($this->getAchivement()->getId()) + $count;
                $this->getAManager()->getPlayerData($player->getName())->setCount($this->getAchivement()->getId(), $newcount);
            }
    }
}