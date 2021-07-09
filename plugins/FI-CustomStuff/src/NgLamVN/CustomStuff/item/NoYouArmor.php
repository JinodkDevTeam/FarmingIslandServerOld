<?php

namespace NgLamVN\CustomStuff\item;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class NoYouArmor implements Listener
{

    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof Player)
        {
            return;
        }
        $armorinv = $entity->getArmorInventory();
        foreach ($armorinv->getContents() as $item)
        {
            $nbt = $item->getNamedTag();
            if (!$nbt->hasTag("CustomItem"))
            {
                continue;
            }
            if ($nbt->getTag("CustomItem")->getValue() == "NoYouArmor")
            {
                $event->setBaseDamage(0);
                break;
            }
        }
    }
}
