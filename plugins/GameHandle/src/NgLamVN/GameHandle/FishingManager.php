<?php

namespace NgLamVN\GameHandle;

use pocketmine\event\player\PlayerFishEvent;
use pocketmine\item\Item;

class FishingManager
{
    public function __construct()
    {
    }

    public function onFish(PlayerFishEvent $event)
    {
        if ($event->getState() == PlayerFishEvent::STATE_CAUGHT_FISH)
        {
            $event->setItemResult($this->getRandomItems());
        }
    }

    public function getRandomItems(): Item
    {
        $items = [];

        $items[1] = Item::get(Item::COBBLESTONE, 0, 5);
        $items[2] = Item::get(Item::DIRT, 0 , 1);
        $items[3] = Item::get(Item::COAL, 0, 2);
        $items[4] = Item::get(Item::IRON_INGOT, 0, 1);
        $items[5] = Item::get(Item::FISH, 0, 1);
        $items[6] = Item::get(Item::GOLD_NUGGET, 0, 2);
        $items[7] = Item::get(Item::LOG, 0, 1);
        $items[8] = Item::get(Item::SAND, 0, 1);
        $items[9] = Item::get(Item::DEAD_BUSH, 0, 1);
        $items[10] = Item::get(Item::CARROT, 0, 1);
        $items[11] = Item::get(Item::BONE, 0, 1);
        $items[12] = Item::get(Item::SALMON, 0, 1);
        $items[13] = Item::get(Item::ROTTEN_FLESH, 0 ,1);

        return $items[array_rand($items)];
    }

}