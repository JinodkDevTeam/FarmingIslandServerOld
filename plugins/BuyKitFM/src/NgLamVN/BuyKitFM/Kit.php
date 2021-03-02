<?php

/*
 *  ____              _  ___ _   ______ __  __
 * |  _ \            | |/ (_) | |  ____|  \/  |
 * | |_) |_   _ _   _| ' / _| |_| |__  | \  / |
 * |  _ <| | | | | | |  < | | __|  __| | |\/| |
 * | |_) | |_| | |_| | . \| | |_| |    | |  | |
 * |____/ \__,_|\__, |_|\_\_|\__|_|    |_|  |_|
 *               __/ |
 *              |___/
 */

namespace NgLamVN\BuyKitFM;

use _64FF00\PurePerms\PurePerms;
use NgLamVN\BuyKitFM\api\BuildItem;
use NgLamVN\BuyKitFM\ItemLock\ItemLock;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Kit
{
    public $name;
    public $items, $effects, $description;
    public $price;

    public function __construct(string $name, array $items, array $effects, array $description, int $price)
    {
        $this->name = $name;
        $this->items = $items;
        $this->effects = $effects;
        $this->description = $description;
        $this->price = $price;
    }

    public function giveItem (Player $player): void
    {
        $make = new BuildItem();
        foreach (array_keys($this->items) as $item)
        {
            $itemdata = $this->items[$item];
            $item = $make->make($itemdata);
            if (isset($itemdata["lock"]))
            {
                if ($itemdata["lock"] == "true")
                {
                    $item = ItemLock::lock($item, $player);
                }
            }
            $player->getInventory()->addItem($item);
        }
    }

    public function giveEffect(Player $player): void
    {
        foreach ($this->effects as $effect)
        {
            $e = explode(":", $effect);
            $player->addEffect(new EffectInstance(Effect::getEffect($e[0]), 999999999, $e[1]));

        }
    }

    public function getDescription(): array
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}