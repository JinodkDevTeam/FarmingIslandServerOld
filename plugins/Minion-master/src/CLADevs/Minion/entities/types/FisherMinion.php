<?php

declare(strict_types=1);

namespace CLADevs\Minion\entities\types;

use CLADevs\Minion\entities\MinionEntity;
use NgLamVN\GameHandle\FishingManager;
use pocketmine\block\BlockIds;
use pocketmine\block\Chest;
use pocketmine\math\Vector3;
use pocketmine\item\Item;

class FisherMinion extends MinionEntity
{
    const NAME = "fisher";

    public function initNameTag(): void
    {
        $this->minionName = $this->player . "'s Fisher";
        parent::initNameTag();
    }

    public function entityBaseTick(int $tickDiff = 1): bool
    {
        $update = parent::entityBaseTick($tickDiff);
        if($this->getLevel()->getServer()->getTick() % 30 == 0)
        {
            //Checks if theres a chest behind him
            if($this->getLookingBehind() instanceof Chest){
                $b = $this->getLookingBehind();
                $this->namedtag->setString("xyz", $b->getX() . ":" . $b->getY() . ":" . $b->getZ());
            }
            //Update the coordinates
            if($this->namedtag->getString("xyz") !== "n")
            {
                if(isset($this->getCoord()[1])){
                    $block = $this->getLevel()->getBlock(new Vector3(intval($this->getCoord()[0]), intval($this->getCoord()[1]), intval($this->getCoord()[2])));
                    if(!$block instanceof Chest){
                        $this->namedtag->setString("xyz", "n");
                    }
                }
            }
            if($this->isChestLinked())
            {
                $i = 0;
                for($x = -1; $x <= 1; $x++)
                {
                    for($z = -1; $z <= 1; $z++)
                    {
                        $pos = $this->add($x, -1, $z);
                        if($this->level->getBlock($pos)->getId() === BlockIds::WATER && $i !== 4)
                        { //4 is middle block
                            $this->lookAt($pos);
                            $this->fish();
                            return $update;
                        }
                        $i++;
                    }
                }
            }
        }
        return $update;
    }

    public function fish(): void
    {
        $items = FishingManager::getInstance()->getRandomItems();
        $b = $this->getLevel()->getBlock(new Vector3(intval($this->getCoord()[0]), intval($this->getCoord()[1]), intval($this->getCoord()[2])));
        $tile = $this->getLevel()->getTile($b);

        if($tile instanceof \pocketmine\tile\Chest){
            $inv = $tile->getInventory();

            foreach ($items as $item) {
                if ($inv->canAddItem($item))
                {
                    $inv->addItem($item);
                }
            }
        }
    }

    public function sendSpawnItems(): void
    {
        $this->getInventory()->setItemInHand(Item::get(Item::FISHING_ROD));
        parent::sendSpawnItems();
    }
}
