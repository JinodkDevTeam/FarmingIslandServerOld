<?php

namespace NgLamVN\CustomStuff\item;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerFishEvent;
use pocketmine\math\Vector3;

class GrapplingHook implements Listener
{

    public function onFish (PlayerFishEvent $event)
    {
        $item = $event->getPlayer()->getInventory()->getItemInHand();
        $nbt = $item->getNamedTag();
        if (!$nbt->hasTag("CustomItem"))
        {
            return;
        }
        if ($nbt->getTag("CustomItem")->getValue() == "GrapplingHook")
        {
            if ($event->getState() !== PlayerFishEvent::STATE_CAUGHT_NOTHING)
            {
                $event->setCancelled();
                return;
            }
            $angler = $event->getPlayer();
            $hook = $event->getHook();
            $d0 = $hook->x - $angler->x;
            $d2 = $hook->y - $angler->y;
            $d4 = $hook->z - $angler->z;
            $d6 = sqrt($d0 * $d0 + $d2 * $d2 + $d4 * $d4);
            $d8 = 0.1;
            $vct = (new Vector3($d0 * $d8, $d2 * $d8 + sqrt($d6) * 0.08, $d4 * $d8))->multiply(2);
            $angler->setMotion($vct);
        }
    }
}
