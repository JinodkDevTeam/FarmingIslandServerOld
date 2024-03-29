<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle;

use pocketmine\event\player\PlayerFishEvent;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ListTag;
use pocketmine\utils\SingletonTrait;

class FishingManager
{
    use SingletonTrait;

    public const R1 = [0, 0, 10, 20, 20, 40, 10];
    public const R2 = [0, 10, 25, 20, 20, 20, 5];
    public const R3 = [0, 20, 30, 20, 20, 7, 3];
    public const R4 = [5, 30, 30, 20, 10, 4, 1];
    public const R5 = [10, 40, 30, 10, 5, 5, 0];
    public const R6 = [15, 40, 20, 10, 5, 0, 0];
    public const R7 = [90, 10, 0, 0, 0, 0, 0];
    public const R8 = [99, 1, 0, 0, 0, 0, 0];

    public const MAX_LEVEL = 8;

    public const RARE_LEVEL = [self::R1, self::R2, self::R3, self::R4, self::R5, self::R6, self::R7, self::R8];

    public const MORE_ITEMS = [100, 50, 50, 25, 25, 10];

    public const COUNT = [0, 1, 2, 3, 4, 5, 10];

    /** @var Item[] */
    public array $items = [];
    /** @var int[] */
    public array $rlevel = [];
    /** @var array[] */
    public array $multiply = [];
    /** @var array[] */
    public array $more_items = [];
    /** @var int[] */
    public array $customItem_rlevel = [];

    public function __construct()
    {
        $item = Item::get(Item::IRON_NUGGET);
        $item->setCustomName("§r§bLazy §fShard");
        $nbt = $item->getNamedTag();
        $nbt->setString("CustomItem", "LazyShard");
        $item->setNamedTag($nbt);

        $item2 = Item::get(Item::BEETROOT_SEEDS);
        $item2->setCustomName("§r§aInferium §fSeed");
        $nbt = $item2->getNamedTag();
        $nbt->setString("CustomItem", "InferiumSeed");
        $item2->setNamedTagEntry(new ListTag(Item::TAG_ENCH, [], NBT::TAG_Compound));

        $this->items = [
            Item::get(Item::COBBLESTONE, 0, 1),
            Item::get(Item::DIRT, 0 , 1),
            Item::get(Item::COAL, 0, 1),
            Item::get(Item::IRON_INGOT, 0, 1),
            Item::get(Item::FISH, 0, 1),
            Item::get(Item::GOLD_INGOT, 0, 1),
            Item::get(Item::LOG, 0, 1),
            Item::get(Item::SAND, 0, 1),
            Item::get(Item::CARROT, 0, 1),
            Item::get(Item::BONE, 0, 1),
            Item::get(Item::SALMON, 0, 1),
            Item::get(Item::ROTTEN_FLESH, 0 ,1),
            Item::get(Item::DIAMOND, 0, 1),
            Item::get(Item::POTATO, 0, 1),
            Item::get(Item::CACTUS, 0, 1),
            Item::get(Item::SUGARCANE, 0, 1),
            Item::get(Item::EMERALD, 0, 1),
            $item,
            $item2
        ];
        $this->rlevel = [
            Item::COBBLESTONE => 1,
            Item::DIRT => 2,
            Item::COAL => 3,
            Item::IRON_INGOT => 3,
            Item::FISH => 2,
            Item::GOLD_INGOT => 4,
            Item::LOG => 2,
            Item::SAND => 2,
            Item::CARROT => 2,
            Item::BONE => 2,
            Item::SALMON => 2,
            Item::ROTTEN_FLESH => 2,
            Item::DIAMOND => 6,
            Item::POTATO => 2,
            Item::CACTUS => 3,
            Item::SUGARCANE => 2,
            Item::EMERALD => 5
        ];

        $this->customItem_rlevel = [
            "LazyShard" => 8,
            "InferiumSeed" => 7
        ];

        $this->build();
    }

    public function build()
    {
        //TODO: Build Multiply items
        for ($i = 0; $i <= (self::MAX_LEVEL - 1); $i++)
        {
            $test = [];
            for ($j = 0; $j <= 6; $j++)
            {
                $chance = self::RARE_LEVEL[$i][$j];
                if ($chance > 0)
                {
                    for ($k = 0; $k < $chance; $k++)
                    {
                        array_push($test, self::COUNT[$j]);
                    }
                }
            }
            shuffle($test);
            $this->multiply[$i] = $test;
        }
        //TODO: Build Chance For More Items

        for ($i = 0; $i < 6; $i++)
        {
            $test = [];
            $chance = self::MORE_ITEMS[$i];
            for ($j = 0; $j < $chance; $j++)
            {
                array_push($test, true);
            }
            for ($j = 0; $j < (100 - $chance); $j++)
            {
                array_push($test, false);
            }
            shuffle($test);
            $this->more_items[$i] = $test;
        }
    }

    public function onFish(PlayerFishEvent $event)
    {
        if ($event->getState() == PlayerFishEvent::STATE_CAUGHT_FISH)
        {
            $event->setItemResult($this->getRandomItems());
        }
    }

    /**
     * @return Item[]
     */
    public function getRandomItems(): array
    {
        $i = 0;
        $more = true;
        $items = [];
        while ($more == true)
        {
            $item = $this->items[array_rand($this->items)];
            if ($item->getNamedTag()->hasTag("CustomItem"))
            {
                $level = $this->customItem_rlevel[$item->getNamedTag()->getTag("CustomItem")->getValue()];
            }
            else
            {
                $level = $this->rlevel[$item->getId()];
            }
            $item->setCount($this->multiply[$level - 1][array_rand($this->multiply[$level - 1])]);
            if ($item->getCount() > 0)
            {
                array_push($items, $item);
            }
            $i++;
            if ($i <= 5)
            {
                $more = $this->more_items[$i][array_rand($this->more_items[$i])];
            }
            else
                {
                    $more = $this->more_items[5][array_rand($this->more_items[5])];
                }
        }
        return $items;
    }
}