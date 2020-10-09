<?php

namespace NgLamVN\GameHandle\AchivementSystem\type;

use pocketmine\event\player\PlayerFishEvent;

class FishType extends BaseType
{
    public function onFish(PlayerFishEvent $event)
    {
        if ($event->getState() !== PlayerFishEvent::STATE_CAUGHT_FISH)
        {
            return;
        }
        $player = $event->getPlayer();
        $loot = $event->getItemResult();
        if ($this->getAchivement()->getItem()->getId() == 0)
        {
            return;
        }
        foreach ($loot as $item)
            if ($this->getAItem()->getId() == $item->getId())
            {
                $itemcount = $item->getCount();
                return;
            }
    }
}