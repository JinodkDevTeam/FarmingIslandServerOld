<?php

namespace NgLamVN\CustomStuff\item;

use NgLamVN\CustomStuff\CustomStuff;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\Player;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Leaves;
use pocketmine\block\Leaves2;

class Crook implements Listener
{
    /** @var bool[] */
    public array $isBreaking = [];
    /** @var int[] */
    public array $breaked = [];

    private CustomStuff $core;

    public function __construct(CustomStuff $core)
    {
        $this->core = $core;
    }

    public function isBreaking(Player $player): bool
    {
        if (isset($this->isBreaking[$player->getName()]))
        {
            return $this->isBreaking[$player->getName()];
        }
        else return false;
    }

    /**
     * @param BlockBreakEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();

        if ($item->getId() !== Item::STICK) return;
        if (!$item->getNamedTag()->hasTag("crook")) return;

        if (($block instanceof Leaves) or ($block instanceof Leaves2))
        {
            if ($this->isBreaking($player))
            {
                return;
            }
            $this->isBreaking[$player->getName()] = true;
            $this->breaked[$player->getName()] = 0;
            $this->CrookMine($block, $item, $player);
            $this->breaked[$player->getName()] = 0;
            $this->isBreaking[$player->getName()] = false;
        }
    }

    public function CrookMine(Block $block, Item $item, Player $player, array &$ignore = [])
    {
        if($block->isValid())
        {
            if ($this->breaked[$player->getName()] > 50)
            {
                return;
            }
            $ignore[] = $block->asVector3()->__toString();
            $this->breaked[$player->getName()]++;
            foreach($block->getAllSides() as $side)
            {
                if((($side instanceof Leaves) or ($side instanceof Leaves2)) and !in_array($side->asVector3()->__toString(), $ignore)) {
                    $this->CrookMine($side, $item, $player, $ignore);
                }
            }
            $block->getLevel()->useBreakOn($block, $item, $player, true);
        }
    }
}