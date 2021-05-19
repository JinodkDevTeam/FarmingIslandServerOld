<?php

namespace NgLamVN\CustomStuff;

use NgLamVN\CustomStuff\item\__init;
use pocketmine\item\Item;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\plugin\PluginBase;

class CustomStuff extends PluginBase
{


    public function onEnable()
    {
        $init = new __init($this);

        /*$edian = new BigEndianNBTStream();
        $item = Item::get(Item::STICK);
        $item->setCustomName("§l§e•§cC§br§do§ao§6k§e•");
        $nbt = $item->getNamedTag();
        $nbt->setByte("crook", 1);
        $item->setNamedTag($nbt);
        $data = bin2hex($edian->writeCompressed($item->nbtSerialize()));
        $this->getLogger()->info($data);*/
    }




}
