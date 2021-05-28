<?php

namespace NgLamVN\CustomStuff\block;

use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ListTag;

class InferiumSeed implements Listener
{

    /**
     * @param BlockBreakEvent $event
     * @priority LOWEST
     * @ignoreCancelled TRUE
     */
    public function onBreak(BlockBreakEvent $event)
    {
        if ($event->getBlock()->getId() == Block::BEETROOT_BLOCK)
        {
            $item = Item::get(Item::BEETROOT_SEEDS);
            $item->setCustomName("§r§aInferium §fSeed");
            $nbt = $item->getNamedTag();
            $nbt->setString("CustomItem", "InferiumSeed");
            $item->setNamedTagEntry(new ListTag(Item::TAG_ENCH, [], NBT::TAG_Compound));

            $event->setDrops([$item]);
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @priority HIGHEST
     * @ignoreCancelled TRUE
     */
    public function onInteract(PlayerInteractEvent $event)
    {
        if ($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK)
        {
            return;
        }
        if ($event->getBlock()->getId() == Block::BEETROOT_BLOCK)
        {
            if ($event->getBlock()->getDamage() == 7)
            {
                $event->getBlock()->getLevel()->setBlock($event->getBlock()->asVector3(), Block::get(Block::BEETROOT_BLOCK), true, true);

                $item = Item::get(Item::BEETROOT);
                $item->setCustomName("§r§aInferium §fEssence");
                $nbt = $item->getNamedTag();
                $nbt->setString("CustomItem", "InferiumEssence");
                $item->setNamedTagEntry(new ListTag(Item::TAG_ENCH, [], NBT::TAG_Compound));

                $event->getBlock()->getLevel()->dropItem($event->getBlock()->asVector3(), $item);
                $event->setCancelled();
            }
        }
    }
}
