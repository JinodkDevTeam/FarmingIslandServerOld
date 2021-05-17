<?php

declare(strict_types=1);

namespace NgLamVN\GrowableSneak\block;

use pocketmine\block\Sapling as PMSapling;
use czechpmdevs\multiworld\generator\normal\object\Tree;
use pocketmine\utils\Random;
use pocketmine\item\Item;
use pocketmine\Player;

class Sapling extends PMSapling
{
    public function onRandomTick() : void
    {
        if($this->level->getFullLightAt($this->x, $this->y, $this->z) >= 8 and mt_rand(1, 7) === 1){
            if(($this->meta & 0x08) === 0x08){
                Tree::growTree($this->getLevelNonNull(), $this->x, $this->y, $this->z, new Random(1,20), $this->getVariant());
            }else{
                $this->meta |= 0x08;
                $this->getLevelNonNull()->setBlock($this, $this, true);
            }
        }
    }

    public function onActivate(Item $item, Player $player = null) : bool{
        if ($item->getId() === Item::DYE and $item->getDamage() === 0x0F) { //Bonemeal
            //TODO: change log type
            Tree::growTree($this->getLevelNonNull(), $this->x, $this->y, $this->z, new Random(mt_rand()), $this->getVariant());

            $item->pop();

            return true;
        }
        return false;
    }
}
