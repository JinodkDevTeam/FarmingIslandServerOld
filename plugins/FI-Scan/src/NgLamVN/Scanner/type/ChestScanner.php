<?php

namespace NgLamVN\Scanner\type;

use NgLamVN\Scanner\Loader;
use pocketmine\block\Chest;

class ChestScanner extends InventoryScanner
{
    public Chest $block;

    public function __construct(Loader $loader, Chest $block)
    {
        parent::__construct($loader);

        $this->block = $block;

        $this->scan();
    }

    public function scan()
    {
        $tile = $this->block->getLevel()->getTile($this->block->asVector3());

        if ($tile instanceof \pocketmine\tile\Chest)
        {
            $inv = $tile->getInventory();

            foreach ($inv->getContents() as $item)
            {
                if (!in_array($item->getId(), self::UNBANNED_ITEMS_ID))
                {
                    $inv->remove($item);
                    $this->deleted = $this->deleted + $item->getCount();
                    continue;
                }
                if ($item->getId() < 255)
                {
                    if (!in_array($item->getId(), self::UNBANNED_BLOCKS_ID))
                    {
                        $inv->remove($item);
                        $this->deleted = $this->deleted + $item->getCount();
                    }
                }
            }
        }
        if ($this->deleted > 0)
        {
            $this->loader->getLogger()->warning("Cleared " . $this->deleted . " items in chest at " . $this->block->getX() . "-" . $this->block->getY() . "-" . $this->block->getZ());
        }
    }
}