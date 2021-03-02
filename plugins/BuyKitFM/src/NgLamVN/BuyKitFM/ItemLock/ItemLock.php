<?php


namespace NgLamVN\BuyKitFM\ItemLock;


use NgLamVN\BuyKitFM\Loader;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Item;
use pocketmine\Player;

class ItemLock implements Listener
{
    public $loader;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
        $this->onEnable();
    }

    public function onEnable ()
    {
        $this->loader->getServer()->getPluginManager()->registerEvents($this, $this->loader);
    }

    public static function unlock (Item $item): Item
    {
        $nbt = $item->getNamedTag();
        $nbt->removeTag("lock");
        $item->setNamedTag($nbt);
        $lores = $item->getLore();
        array_pop($lores);
        $item->setLore($lores);

        return $item;
    }

    public static function lock(Item $item, Player $player): Item
    {
        $name = $player->getName();
        $nbt = $item->getNamedTag();
        $nbt->setString("lock", $name);
        $item->setNamedTag($nbt);

        $lores = $item->getLore();
        array_push($lores, "[LOCKED] " .$player->getName());
        $item->setLore($lores);

        return $item;
    }

    /**
     * @param InventoryPickupItemEvent $event
     * @priority LOW
     */

    public function onPickup (InventoryPickupItemEvent $event)
    {
        $viewers = $event->getViewers();
        $item = $event->getItem()->getItem();
        $nbt = $item->getNamedTag();
        if (!$nbt->hasTag("lock"))
        {
            return;
        }
        $owner = $nbt->getString("lock");
        foreach ($viewers as $viewer)
        {
            if ($viewer->getName() !== $owner)
            {
                $event->setCancelled();
            }
        }
    }

    public function onHeld (PlayerItemHeldEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $nbt = $item->getNamedTag();
        if (!$nbt->hasTag("lock"))
        {
            return;
        }
        $owner = $nbt->getString("lock");
        if ($player->getName() !== $owner)
        {
            $index = mt_rand(0,35);
            while ($index == $player->getInventory()->getHeldItemIndex())
            {
                $index = mt_rand(0,35);
            }
            $item1 = $player->getInventory()->getItem($event->getSlot());
            $item2 = $player->getInventory()->getItem($index);
            $player->getInventory()->setItem($event->getSlot(), $item2);
            $player->getInventory()->setItem($index, $item1);
        }
    }

    public function onEquip (EntityArmorChangeEvent $event)
    {
        $entity = $event->getEntity();
        if (!($entity instanceof Player))
        {
            return;
        }
        $item = $event->getNewItem();
        $nbt = $item->getNamedTag();
        if (!$nbt->hasTag("lock"))
        {
            return;
        }
        $owner = $nbt->getString("lock");
        if ($entity->getName() !== $owner)
        {
            $event->setCancelled();
        }
    }

    /*public function onTrans (InventoryTransactionEvent $event)
    {
        $trasaction = $event->getTransaction();
        $actions = $trasaction->getActions();
        $player = $trasaction->getSource();
        foreach ($actions as $action)
        {
            $item1 = $action->getSourceItem();
            $item2 = $action->getTargetItem();
            if ($item1->getNamedTag()->hasTag("lock"))
            {
                if ($player->getName() !== $item1->getNamedTag()->getString("lock"))
                {
                    $event->setCancelled();
                }
            }
            if ($item2->getNamedTag()->hasTag("lock"))
            {
                if ($player->getName() !== $item2->getNamedTag()->getString("lock"))
                {
                    $event->setCancelled();
                }
            }
        }
    }*/
}