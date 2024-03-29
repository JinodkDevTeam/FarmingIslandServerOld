<?php

namespace NgLamVN\CustomStuff\item;

use NgLamVN\CustomStuff\CustomStuff;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\Listener;
use pocketmine\inventory\ArmorInventory;
use pocketmine\Player;

class DivingHelmet implements Listener
{
    public CustomStuff $core;

    public function __construct(CustomStuff $core)
    {
        $this->core = $core;
    }

    /**
     * @param EntityArmorChangeEvent $event
     * @priority HIGHEST
     * @ignoreCancelled TRUE
     */
    public function onArmorChange (EntityArmorChangeEvent $event)
    {
        $entity = $event->getEntity();
        if (!$entity instanceof Player)
        {
            return;
        }
        if ($event->getSlot() !== ArmorInventory::SLOT_HEAD)
        {
            return;
        }
        $item = $event->getNewItem();
        $nbt = $item->getNamedTag();
        if (!$nbt->hasTag("CustomItem"))
        {
            $this->removeEffect($entity);
            return;
        }
        if ($nbt->getTag("CustomItem")->getValue() == "DivingHelmet")
        {
            $this->addEffect($entity);
        }
        else
        {
            $this->removeEffect($entity);
        }
    }

    public function addEffect(Player $player)
    {
        $effect = Effect::getEffect(Effect::WATER_BREATHING);
        $player->addEffect(new EffectInstance($effect, 2147483647, 0, false));
    }

    public function removeEffect(Player $player)
    {
        if ($player->hasEffect(Effect::WATER_BREATHING))
        {
            $player->removeEffect(Effect::WATER_BREATHING);
        }
    }
}
